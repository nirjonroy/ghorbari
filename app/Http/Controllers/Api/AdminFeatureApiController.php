<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Admin;
use App\Models\Agency;
use App\Models\AgentProfile;
use App\Models\Amenity;
use App\Models\Area;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogPage;
use App\Models\BlogPost;
use App\Models\City;
use App\Models\ContactMessage;
use App\Models\CustomPage;
use App\Models\District;
use App\Models\Division;
use App\Models\Property;
use App\Models\PropertyMedia;
use App\Models\PropertyPriceHistory;
use App\Models\PropertyType;
use App\Models\SiteInfo;
use App\Models\Slider;
use App\Models\User;
use App\Services\ImageUploadService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminFeatureApiController extends Controller
{
    public function dashboard(): JsonResponse
    {
        return response()->json([
            'data' => [
                'total_properties' => Property::count(),
                'published_properties' => Property::where('is_published', true)->count(),
                'registered_users' => User::count(),
                'pending_verification' => Property::where('verification_status', 'pending')->count(),
                'blog_posts' => BlogPost::count(),
                'approved_comments' => BlogComment::where('is_approved', true)->count(),
                'active_sliders' => Slider::where('status', true)->count(),
            ],
        ]);
    }

    public function index(Request $request, string $resource): JsonResponse
    {
        $config = $this->config($resource);
        $query = $config['model']::query();

        if (! empty($config['with'])) {
            $query->with($config['with']);
        }

        foreach ($config['order'] ?? [['id', 'desc']] as $order) {
            $query->orderBy($order[0], $order[1]);
        }

        $perPage = (int) $request->query('per_page', 15);

        return response()->json([
            'data' => $perPage > 0
                ? $query->paginate(min($perPage, 100))
                : $query->get(),
        ]);
    }

    public function show(string $resource, int $id): JsonResponse
    {
        return response()->json([
            'data' => $this->findModel($resource, $id),
        ]);
    }

    public function store(Request $request, string $resource): JsonResponse
    {
        $config = $this->config($resource);
        $data = $this->validatedData($request, $resource);
        $model = $this->createModel($request, $resource, $config, $data);

        return response()->json([
            'message' => Str::headline($resource).' created successfully.',
            'data' => $this->freshModel($resource, $model),
        ], 201);
    }

    public function update(Request $request, string $resource, int $id): JsonResponse
    {
        $config = $this->config($resource);
        $model = $this->findModel($resource, $id);
        $data = $this->validatedData($request, $resource, $model);
        $this->updateModel($request, $resource, $config, $model, $data);

        return response()->json([
            'message' => Str::headline($resource).' updated successfully.',
            'data' => $this->freshModel($resource, $model),
        ]);
    }

    public function destroy(string $resource, int $id): JsonResponse
    {
        $model = $this->findModel($resource, $id);
        $this->deleteFiles($resource, $model);
        $model->delete();

        return response()->json([
            'message' => Str::headline($resource).' deleted successfully.',
        ]);
    }

    public function destroyPropertyMedia(PropertyMedia $media): JsonResponse
    {
        if ($media->file_path && File::exists(public_path($media->file_path))) {
            File::delete(public_path($media->file_path));
        }

        $media->delete();

        return response()->json([
            'message' => 'Property media deleted successfully.',
        ]);
    }

    private function createModel(Request $request, string $resource, array $config, array $data): Model
    {
        if ($resource === 'site-info') {
            return $this->saveSiteInfo($request, null, $data);
        }

        if ($resource === 'admins') {
            $roles = $data['roles'] ?? [];
            unset($data['roles']);
            $data['password'] = Hash::make($data['password']);
            $admin = Admin::create($data);
            $admin->syncRoles($roles);

            return $admin;
        }

        if ($resource === 'users') {
            $data['password'] = Hash::make($data['password']);

            return User::create($data);
        }

        if ($resource === 'roles') {
            $permissions = $data['permissions'] ?? [];
            unset($data['permissions']);
            $role = Role::create($data + ['guard_name' => 'admin']);
            $role->syncPermissions($permissions);

            return $role;
        }

        if ($resource === 'permissions') {
            return Permission::create($data + ['guard_name' => 'admin']);
        }

        $data = $this->handleFiles($request, $resource, $data);

        if ($resource === 'properties') {
            $amenities = $data['amenities'] ?? [];
            unset($data['amenities'], $data['media_files'], $data['media_space_names']);
            $property = Property::create($data);
            $property->amenities()->sync($amenities);
            $this->storePropertyMedia($request, $property);
            $this->recordPriceHistory($property, null, $property->price);

            return $property;
        }

        return $config['model']::create($data);
    }

    private function updateModel(Request $request, string $resource, array $config, Model $model, array $data): void
    {
        if ($resource === 'site-info') {
            $this->saveSiteInfo($request, $model, $data);

            return;
        }

        if ($resource === 'admins') {
            $roles = $data['roles'] ?? null;
            unset($data['roles']);

            if (! empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $model->update($data);

            if (is_array($roles)) {
                $model->syncRoles($roles);
            }

            return;
        }

        if ($resource === 'users') {
            if (! empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $model->update($data);

            return;
        }

        if ($resource === 'roles') {
            $permissions = $data['permissions'] ?? null;
            unset($data['permissions']);
            $model->update($data);

            if (is_array($permissions)) {
                $model->syncPermissions($permissions);
            }

            return;
        }

        $data = $this->handleFiles($request, $resource, $data, $model);

        if ($resource === 'properties') {
            $oldPrice = $model->price;
            $amenities = $data['amenities'] ?? null;
            unset($data['amenities'], $data['media_files'], $data['media_space_names']);
            $model->update($data);

            if (is_array($amenities)) {
                $model->amenities()->sync($amenities);
            }

            $this->storePropertyMedia($request, $model);

            if (isset($data['price']) && (string) $oldPrice !== (string) $model->price) {
                $this->recordPriceHistory($model, $oldPrice, $model->price);
            }

            return;
        }

        $model->update($data);
    }

    private function validatedData(Request $request, string $resource, ?Model $model = null): array
    {
        if ($resource === 'custom-pages' && $request->has('url_path')) {
            $request->merge([
                'url_path' => trim(preg_replace('#/+#', '/', trim((string) $request->input('url_path'))), '/'),
            ]);
        }

        $rules = match ($resource) {
            'site-info' => $this->siteInfoRules(),
            'abouts' => $this->aboutRules($model),
            'sliders' => $this->sliderRules(),
            'users' => $this->userRules($model),
            'agencies' => $this->agencyRules($model),
            'agent-profiles' => $this->agentProfileRules($model),
            'property-types', 'amenities' => $this->nameSlugRules($resource === 'property-types' ? 'property_types' : 'amenities', $model, true),
            'properties' => $this->propertyRules($model),
            'divisions' => $this->locationRules('divisions', $model),
            'districts' => $this->districtRules($model),
            'cities' => $this->cityRules($model),
            'areas' => $this->areaRules($model),
            'blog-categories' => $this->blogCategoryRules($model),
            'blog-posts' => $this->blogPostRules($model),
            'blog-comments' => $this->blogCommentRules(),
            'blog-pages' => $this->blogPageRules(),
            'custom-pages' => $this->customPageRules($model),
            'contact-messages' => $this->contactMessageRules(),
            'roles' => $this->roleRules($model),
            'permissions' => $this->permissionRules($model),
            'admins' => $this->adminRules($model),
            default => abort(404, 'API resource not found.'),
        };

        $data = $request->validate($rules);

        foreach (['is_active', 'is_published', 'show_on_home', 'is_approved', 'is_featured', 'is_early_access', 'is_open_house'] as $field) {
            if ($request->has($field)) {
                $data[$field] = $request->boolean($field);
            }
        }

        if (in_array($resource, ['sliders', 'divisions', 'districts', 'cities', 'areas'], true) && $request->has('status')) {
            $data['status'] = $request->boolean('status');
        }

        foreach (['maintenance_mode', 'enable_user_register', 'phone_number_required', 'enable_subscription_notify', 'enable_save_contact_message'] as $field) {
            if ($request->has($field)) {
                $data[$field] = $request->boolean($field);
            }
        }

        if (array_key_exists('tags_input', $data)) {
            $data['tags'] = $this->commaSeparatedValues($data['tags_input']);
            unset($data['tags_input']);
        }

        if (isset($data['slug']) || in_array($resource, ['abouts', 'agencies', 'property-types', 'amenities', 'divisions', 'districts', 'cities', 'areas', 'blog-categories', 'blog-posts', 'custom-pages', 'properties'], true)) {
            $data['slug'] = $this->uniqueSlug($resource, $data['slug'] ?? ($data['title'] ?? $data['page_name'] ?? $data['name'] ?? 'item'), $model);
        }

        if ($resource === 'custom-pages') {
            $data['template_type'] = 'default';
        }

        return $data;
    }

    private function findModel(string $resource, int $id): Model
    {
        $config = $this->config($resource);
        $query = $config['model']::query();

        if (! empty($config['with'])) {
            $query->with($config['with']);
        }

        return $query->findOrFail($id);
    }

    private function freshModel(string $resource, Model $model): Model
    {
        $config = $this->config($resource);

        return $config['model']::query()
            ->when(! empty($config['with']), fn ($query) => $query->with($config['with']))
            ->findOrFail($model->id);
    }

    private function config(string $resource): array
    {
        $configs = [
            'site-info' => ['model' => SiteInfo::class],
            'abouts' => ['model' => About::class, 'order' => [['display_order', 'asc'], ['id', 'desc']]],
            'sliders' => ['model' => Slider::class, 'order' => [['serial', 'asc'], ['id', 'desc']]],
            'users' => ['model' => User::class],
            'agencies' => ['model' => Agency::class, 'with' => ['agents'], 'order' => [['name', 'asc']]],
            'agent-profiles' => ['model' => AgentProfile::class, 'with' => ['user', 'agency']],
            'property-types' => ['model' => PropertyType::class, 'order' => [['name', 'asc']]],
            'amenities' => ['model' => Amenity::class, 'order' => [['name', 'asc']]],
            'properties' => ['model' => Property::class, 'with' => ['owner', 'type', 'amenities', 'media']],
            'divisions' => ['model' => Division::class, 'order' => [['name', 'asc']]],
            'districts' => ['model' => District::class, 'with' => ['division'], 'order' => [['name', 'asc']]],
            'cities' => ['model' => City::class, 'with' => ['district.division'], 'order' => [['name', 'asc']]],
            'areas' => ['model' => Area::class, 'with' => ['district.division', 'city'], 'order' => [['name', 'asc']]],
            'blog-categories' => ['model' => BlogCategory::class, 'order' => [['display_order', 'asc'], ['name', 'asc']]],
            'blog-posts' => ['model' => BlogPost::class, 'with' => ['category', 'comments']],
            'blog-comments' => ['model' => BlogComment::class, 'with' => ['post', 'user']],
            'blog-pages' => ['model' => BlogPage::class],
            'custom-pages' => ['model' => CustomPage::class, 'order' => [['id', 'desc']]],
            'contact-messages' => ['model' => ContactMessage::class, 'with' => ['user']],
            'roles' => ['model' => Role::class, 'with' => ['permissions'], 'order' => [['name', 'asc']]],
            'permissions' => ['model' => Permission::class, 'order' => [['name', 'asc']]],
            'admins' => ['model' => Admin::class, 'with' => ['roles', 'permissions']],
        ];

        abort_unless(isset($configs[$resource]), 404, 'API resource not found.');

        return $configs[$resource];
    }

    private function siteInfoRules(): array
    {
        return [
            'google_location' => ['nullable', 'string'],
            'footer_google_location' => ['nullable', 'string'],
            'footer_contact_note' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'favicon' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1024'],
            'logo_width' => ['nullable', 'integer', 'min:1', 'max:5000'],
            'logo_height' => ['nullable', 'integer', 'min:1', 'max:5000'],
            'favicon_width' => ['nullable', 'integer', 'min:1', 'max:512'],
            'favicon_height' => ['nullable', 'integer', 'min:1', 'max:512'],
            'image_output_format' => ['nullable', 'in:jpg,png,webp,original'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'text_direction' => ['nullable', 'in:ltr,rtl'],
            'default_theme' => ['nullable', 'in:light,dark'],
            'timezone' => ['nullable', 'string', 'max:255'],
            'sidebar_lg_header' => ['nullable', 'string', 'max:255'],
            'sidebar_sm_header' => ['nullable', 'string', 'max:255'],
            'topbar_phone' => ['nullable', 'string', 'max:255'],
            'topbar_email' => ['nullable', 'email', 'max:255'],
            'currency_name' => ['nullable', 'string', 'max:255'],
            'currency_icon' => ['nullable', 'string', 'max:255'],
            'currency_rate' => ['nullable', 'numeric', 'min:0'],
            'default_phone_code' => ['nullable', 'string', 'max:255'],
            'frontend_url' => ['nullable', 'url', 'max:255'],
            'homepage_section_title' => ['nullable', 'string', 'max:255'],
            'slider_width' => ['nullable', 'integer', 'min:1', 'max:5000'],
            'slider_height' => ['nullable', 'integer', 'min:1', 'max:5000'],
            'about_image_width' => ['nullable', 'integer', 'min:1', 'max:5000'],
            'about_image_height' => ['nullable', 'integer', 'min:1', 'max:5000'],
            'property_image_width' => ['nullable', 'integer', 'min:1', 'max:5000'],
            'property_image_height' => ['nullable', 'integer', 'min:1', 'max:5000'],
            'blog_post_image_width' => ['nullable', 'integer', 'min:1', 'max:5000'],
            'blog_post_image_height' => ['nullable', 'integer', 'min:1', 'max:5000'],
            'blog_page_image_width' => ['nullable', 'integer', 'min:1', 'max:5000'],
            'blog_page_image_height' => ['nullable', 'integer', 'min:1', 'max:5000'],
            'agency_logo_width' => ['nullable', 'integer', 'min:1', 'max:5000'],
            'agency_logo_height' => ['nullable', 'integer', 'min:1', 'max:5000'],
            'maintenance_mode' => ['nullable', 'boolean'],
            'enable_user_register' => ['nullable', 'boolean'],
            'phone_number_required' => ['nullable', 'boolean'],
            'enable_subscription_notify' => ['nullable', 'boolean'],
            'enable_save_contact_message' => ['nullable', 'boolean'],
        ];
    }

    private function aboutRules(?Model $model): array
    {
        return [
            'title' => [$model ? 'sometimes' : 'required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string'],
            'long_description' => [$model ? 'sometimes' : 'required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'image_alt_text' => ['nullable', 'string', 'max:255'],
            'mission_title' => ['nullable', 'string', 'max:255'],
            'mission_description' => ['nullable', 'string'],
            'vision_title' => ['nullable', 'string', 'max:255'],
            'vision_description' => ['nullable', 'string'],
            'display_order' => [$model ? 'sometimes' : 'required', 'integer', 'min:0'],
            'status' => ['nullable', 'string', 'max:50'],
        ];
    }

    private function sliderRules(): array
    {
        return [
            'title_one' => ['nullable', 'string', 'max:255'],
            'title_two' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'link' => ['nullable', 'url', 'max:255'],
            'serial' => ['nullable', 'integer', 'min:0'],
            'slider_location' => ['nullable', 'string', 'max:255'],
            'product_slug' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'boolean'],
        ];
    }

    private function customPageRules(?Model $model): array
    {
        return [
            'page_name' => [$model ? 'sometimes' : 'required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'url_path' => [$model ? 'sometimes' : 'required', 'string', 'max:255', Rule::unique('custom_pages', 'url_path')->ignore($model)],
            'template_type' => ['nullable', 'string', 'max:80'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string'],
            'content' => [$model ? 'sometimes' : 'required', 'string'],
            'background_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'status' => ['nullable', Rule::in(['draft', 'published', 'inactive'])],
            'published_at' => ['nullable', 'date'],
        ];
    }

    private function userRules(?Model $model): array
    {
        return [
            'name' => [$model ? 'sometimes' : 'required', 'string', 'max:255'],
            'account_type' => ['nullable', 'string', 'max:255'],
            'email' => [$model ? 'sometimes' : 'required', 'email', 'max:255', Rule::unique('users')->ignore($model)],
            'phone' => ['nullable', 'string', 'max:255'],
            'alternative_phone' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:255'],
            'profession' => ['nullable', 'string', 'max:255'],
            'home_name' => ['nullable', 'string', 'max:255'],
            'home_type' => ['nullable', 'string', 'max:255'],
            'present_address' => ['nullable', 'string'],
            'permanent_address' => ['nullable', 'string'],
            'area_name' => ['nullable', 'string', 'max:255'],
            'post_office' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:255'],
            'upazila' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'division' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'profile_photo_path' => ['nullable', 'string', 'max:255'],
            'nid_number' => ['nullable', 'string', 'max:255'],
            'nid_front_image_path' => ['nullable', 'string', 'max:255'],
            'nid_back_image_path' => ['nullable', 'string', 'max:255'],
            'passport_number' => ['nullable', 'string', 'max:255'],
            'passport_image_path' => ['nullable', 'string', 'max:255'],
            'ownership_document_type' => ['nullable', 'string', 'max:255'],
            'ownership_proof_path' => ['nullable', 'string', 'max:255'],
            'home_elevation_image_paths' => ['nullable', 'array'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:255'],
            'password' => [$model ? 'nullable' : 'required', 'string', 'min:8'],
        ];
    }

    private function agencyRules(?Model $model): array
    {
        return [
            'name' => [$model ? 'sometimes' : 'required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:180', Rule::unique('agencies', 'slug')->ignore($model)],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'website' => ['nullable', 'url', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'string', 'max:50'],
        ];
    }

    private function agentProfileRules(?Model $model): array
    {
        return [
            'user_id' => [$model ? 'sometimes' : 'required', 'exists:users,id', Rule::unique('agent_profiles', 'user_id')->ignore($model)],
            'agency_id' => ['nullable', 'exists:agencies,id'],
            'designation' => ['nullable', 'string', 'max:150'],
            'license_no' => ['nullable', 'string', 'max:100'],
            'bio' => ['nullable', 'string'],
            'experience_years' => ['nullable', 'integer', 'min:0', 'max:100'],
            'service_area' => ['nullable', 'string', 'max:255'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'status' => ['nullable', 'string', 'max:50'],
        ];
    }

    private function nameSlugRules(string $table, ?Model $model, bool $hasIcon = false): array
    {
        return [
            'name' => [$model ? 'sometimes' : 'required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:120', Rule::unique($table, 'slug')->ignore($model)],
            'icon' => [$hasIcon ? 'nullable' : 'prohibited', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'max:50'],
        ];
    }

    private function propertyRules(?Model $model): array
    {
        return [
            'owner_user_id' => [$model ? 'sometimes' : 'required', 'exists:users,id'],
            'agent_profile_id' => ['nullable', 'integer', 'min:1'],
            'agency_id' => ['nullable', 'integer', 'min:1'],
            'property_type_id' => [$model ? 'sometimes' : 'required', 'exists:property_types,id'],
            'property_category' => ['nullable', Rule::in(['residential', 'commercial', 'land', 'industrial'])],
            'address_id' => [$model ? 'sometimes' : 'required', 'integer', 'min:1'],
            'district_id' => ['nullable', 'exists:districts,id'],
            'city_id' => ['nullable', 'exists:cities,id'],
            'area_id' => ['nullable', 'exists:areas,id'],
            'title' => [$model ? 'sometimes' : 'required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('properties', 'slug')->ignore($model)],
            'listing_type' => [$model ? 'sometimes' : 'required', Rule::in(['buy', 'sell', 'rent'])],
            'property_status' => ['nullable', Rule::in(['available', 'sold', 'rented', 'pending'])],
            'price' => [$model ? 'sometimes' : 'required', 'numeric', 'min:0'],
            'rent_period' => ['nullable', Rule::in(['monthly', 'yearly'])],
            'area_size' => ['nullable', 'numeric', 'min:0'],
            'land_size' => ['nullable', 'numeric', 'min:0'],
            'bedrooms' => ['nullable', 'integer', 'min:0'],
            'bathrooms' => ['nullable', 'integer', 'min:0'],
            'balconies' => ['nullable', 'integer', 'min:0'],
            'floor_no' => ['nullable', 'integer', 'min:0'],
            'total_floors' => ['nullable', 'integer', 'min:0'],
            'parking_spaces' => ['nullable', 'integer', 'min:0'],
            'furnishing_status' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'verification_status' => ['nullable', Rule::in(['pending', 'approved', 'rejected'])],
            'is_featured' => ['nullable', 'boolean'],
            'is_early_access' => ['nullable', 'boolean'],
            'is_open_house' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['integer', 'exists:amenities,id'],
            'media_files' => ['nullable', 'array'],
            'media_files.*' => ['nullable', 'array'],
            'media_files.*.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,mp4,mov,pdf', 'max:10240'],
            'media_space_names' => ['nullable', 'array'],
            'media_space_names.*' => ['nullable', 'string', 'max:255'],
        ];
    }

    private function locationRules(string $table, ?Model $model): array
    {
        return [
            'name' => [$model ? 'sometimes' : 'required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique($table, 'slug')->ignore($model)],
            'status' => ['nullable', 'boolean'],
        ];
    }

    private function districtRules(?Model $model): array
    {
        return $this->locationRules('districts', $model) + [
            'division_id' => [$model ? 'sometimes' : 'required', 'exists:divisions,id'],
        ];
    }

    private function areaRules(?Model $model): array
    {
        return $this->locationRules('areas', $model) + [
            'district_id' => [$model ? 'sometimes' : 'required', 'exists:districts,id'],
            'city_id' => ['nullable', 'exists:cities,id'],
            'post_office' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:255'],
        ];
    }

    private function cityRules(?Model $model): array
    {
        return $this->locationRules('cities', $model) + [
            'district_id' => [$model ? 'sometimes' : 'required', 'exists:districts,id'],
        ];
    }

    private function blogCategoryRules(?Model $model): array
    {
        return [
            'name' => [$model ? 'sometimes' : 'required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('blog_categories', 'slug')->ignore($model)],
            'description' => ['nullable', 'string'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    private function blogPostRules(?Model $model): array
    {
        return [
            'blog_category_id' => ['nullable', 'exists:blog_categories,id'],
            'title' => [$model ? 'sometimes' : 'required', 'string', 'max:255', Rule::unique('blog_posts', 'title')->ignore($model)],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('blog_posts', 'slug')->ignore($model)],
            'author_name' => [$model ? 'sometimes' : 'required', 'string', 'max:255'],
            'excerpt' => [$model ? 'sometimes' : 'required', 'string'],
            'content' => [$model ? 'sometimes' : 'required', 'string'],
            'quote' => ['nullable', 'string'],
            'featured_image_path' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'featured_image_source' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'tags_input' => ['nullable', 'string'],
            'tags' => ['nullable', 'array'],
            'published_at' => ['nullable', 'date'],
            'is_published' => ['nullable', 'boolean'],
            'show_on_home' => ['nullable', 'boolean'],
        ];
    }

    private function blogCommentRules(): array
    {
        return [
            'blog_post_id' => ['required', 'exists:blog_posts,id'],
            'user_id' => ['nullable', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'comment' => ['required', 'string'],
            'is_approved' => ['nullable', 'boolean'],
        ];
    }

    private function blogPageRules(): array
    {
        return [
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_background_path' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'hero_background_source' => ['nullable', 'string', 'max:255'],
            'home_section_title' => ['nullable', 'string', 'max:255'],
            'categories_title' => ['nullable', 'string', 'max:255'],
            'recommendation_title' => ['nullable', 'string', 'max:255'],
            'latest_posts_title' => ['nullable', 'string', 'max:255'],
            'tags_title' => ['nullable', 'string', 'max:255'],
            'read_button_text' => ['nullable', 'string', 'max:255'],
            'article_tags_title' => ['nullable', 'string', 'max:255'],
            'comments_section_title' => ['nullable', 'string', 'max:255'],
        ];
    }

    private function contactMessageRules(): array
    {
        return [
            'user_id' => ['nullable', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message_type' => ['nullable', 'string', 'max:100'],
            'message' => ['required', 'string'],
            'source_page' => ['nullable', 'string', 'max:255'],
            'ip_address' => ['nullable', 'string', 'max:100'],
            'user_agent' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', Rule::in(['new', 'read', 'replied', 'closed', 'spam'])],
            'admin_note' => ['nullable', 'string'],
            'read_at' => ['nullable', 'date'],
            'replied_at' => ['nullable', 'date'],
        ];
    }

    private function roleRules(?Model $model): array
    {
        return [
            'name' => [$model ? 'sometimes' : 'required', 'string', 'max:255', Rule::unique('roles')->where('guard_name', 'admin')->ignore($model)],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ];
    }

    private function permissionRules(?Model $model): array
    {
        return [
            'name' => [$model ? 'sometimes' : 'required', 'string', 'max:255', Rule::unique('permissions')->where('guard_name', 'admin')->ignore($model)],
        ];
    }

    private function adminRules(?Model $model): array
    {
        return [
            'name' => [$model ? 'sometimes' : 'required', 'string', 'max:255'],
            'email' => [$model ? 'sometimes' : 'required', 'email', 'max:255', Rule::unique('admins')->ignore($model)],
            'password' => [$model ? 'nullable' : 'required', 'string', 'min:8'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', 'exists:roles,name'],
        ];
    }

    private function handleFiles(Request $request, string $resource, array $data, ?Model $model = null): array
    {
        $siteInfo = SiteInfo::query()->first();
        $uploader = new ImageUploadService();

        $fields = match ($resource) {
            'abouts' => [
                'image' => ['directory' => 'uploads/abouts', 'width' => $siteInfo?->about_image_width, 'height' => $siteInfo?->about_image_height],
            ],
            'sliders' => [
                'image' => ['directory' => 'uploads/sliders', 'width' => $siteInfo?->slider_width, 'height' => $siteInfo?->slider_height],
            ],
            'agencies' => [
                'logo' => ['directory' => 'uploads/agencies', 'width' => $siteInfo?->agency_logo_width, 'height' => $siteInfo?->agency_logo_height],
            ],
            'blog-posts' => [
                'featured_image_path' => ['directory' => 'uploads/blog/posts', 'width' => $siteInfo?->blog_post_image_width, 'height' => $siteInfo?->blog_post_image_height],
            ],
            'blog-pages' => [
                'hero_background_path' => ['directory' => 'uploads/blog/pages', 'width' => $siteInfo?->blog_page_image_width, 'height' => $siteInfo?->blog_page_image_height],
            ],
            'custom-pages' => [
                'background_image' => ['directory' => 'uploads/custom-pages/backgrounds', 'width' => $siteInfo?->blog_page_image_width ?: 1920, 'height' => $siteInfo?->blog_page_image_height ?: 560],
                'meta_image' => ['directory' => 'uploads/custom-pages', 'width' => $siteInfo?->blog_page_image_width, 'height' => $siteInfo?->blog_page_image_height],
            ],
            default => [],
        };

        foreach ($fields as $field => $options) {
            unset($data[$field]);

            if ($request->hasFile($field)) {
                $data[$field] = $uploader->storeConverted(
                    $request->file($field),
                    $options['directory'],
                    $options['width'],
                    $options['height'],
                    $model?->{$field},
                    $siteInfo?->image_output_format ?? 'webp'
                );
            }
        }

        return $data;
    }

    private function saveSiteInfo(Request $request, ?Model $siteInfo, array $data): SiteInfo
    {
        $data['image_output_format'] = $data['image_output_format'] ?? $siteInfo?->image_output_format ?? 'webp';
        $data['text_direction'] = $data['text_direction'] ?? $siteInfo?->text_direction ?? 'ltr';
        $data['default_theme'] = $data['default_theme'] ?? $siteInfo?->default_theme ?? 'light';
        $data['timezone'] = $data['timezone'] ?? $siteInfo?->timezone ?? config('app.timezone');
        $data['currency_rate'] = $data['currency_rate'] ?? $siteInfo?->currency_rate ?? 1;

        $uploader = new ImageUploadService();

        foreach (['logo', 'favicon'] as $field) {
            unset($data[$field]);

            if ($request->hasFile($field)) {
                $data[$field] = $uploader->storeConverted(
                    $request->file($field),
                    'uploads/siteinfo',
                    $data[$field.'_width'] ?? $siteInfo?->{$field.'_width'},
                    $data[$field.'_height'] ?? $siteInfo?->{$field.'_height'},
                    $siteInfo?->{$field},
                    $data['image_output_format']
                );
            }
        }

        return SiteInfo::query()->updateOrCreate(['id' => $siteInfo?->id], $data);
    }

    private function storePropertyMedia(Request $request, Property $property): void
    {
        if (! $request->hasFile('media_files')) {
            return;
        }

        $siteInfo = SiteInfo::query()->first();
        $uploader = new ImageUploadService();
        $spaceNames = $request->input('media_space_names', []);

        foreach ($request->file('media_files', []) as $index => $files) {
            foreach ((array) $files as $file) {
                if (! $file) {
                    continue;
                }

                $mediaType = str_starts_with($file->getMimeType(), 'video/') ? 'video' : (str_contains($file->getMimeType(), 'pdf') ? 'floor_plan' : 'image');
                $spaceName = trim($spaceNames[$index] ?? '');

                if ($mediaType === 'image') {
                    $path = $uploader->storeConverted(
                        $file,
                        'uploads/properties',
                        $siteInfo?->property_image_width,
                        $siteInfo?->property_image_height,
                        null,
                        $siteInfo?->image_output_format ?? 'webp'
                    );
                } else {
                    $directory = public_path('uploads/properties');
                    if (! File::isDirectory($directory)) {
                        File::makeDirectory($directory, 0755, true);
                    }

                    $fileName = (Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'media').'-'.uniqid().'.'.$file->getClientOriginalExtension();
                    $file->move($directory, $fileName);
                    $path = 'uploads/properties/'.$fileName;
                }

                $property->media()->create([
                    'media_type' => $mediaType,
                    'space_name' => $spaceName ?: null,
                    'file_path' => $path,
                    'alt_text' => $spaceName ? $property->title.' - '.$spaceName : $property->title,
                    'is_primary' => ! $property->media()->exists(),
                    'sort_order' => $property->media()->count(),
                ]);
            }
        }
    }

    private function deleteFiles(string $resource, Model $model): void
    {
        $fields = match ($resource) {
            'site-info' => ['logo', 'favicon'],
            'abouts', 'sliders' => ['image'],
            'blog-posts' => ['featured_image_path'],
            'blog-pages' => ['hero_background_path'],
            'custom-pages' => ['background_image', 'meta_image'],
            'agencies' => ['logo'],
            default => [],
        };

        foreach ($fields as $field) {
            if ($model->{$field} && File::exists(public_path($model->{$field}))) {
                File::delete(public_path($model->{$field}));
            }
        }

        if ($resource === 'properties') {
            foreach ($model->media as $media) {
                if ($media->file_path && File::exists(public_path($media->file_path))) {
                    File::delete(public_path($media->file_path));
                }
            }
        }
    }

    private function recordPriceHistory(Property $property, $oldPrice, $newPrice): void
    {
        PropertyPriceHistory::create([
            'property_id' => $property->id,
            'old_price' => $oldPrice,
            'new_price' => $newPrice,
            'changed_by' => request()->user()?->id,
            'changed_at' => now(),
        ]);
    }

    private function uniqueSlug(string $resource, string $value, ?Model $model = null): string
    {
        $config = $this->config($resource);
        $baseSlug = Str::slug($value) ?: Str::singular($resource);
        $slug = $baseSlug;
        $counter = 2;

        while ($config['model']::query()
            ->where('slug', $slug)
            ->when($model, fn ($query) => $query->where('id', '!=', $model->id))
            ->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    private function commaSeparatedValues(?string $value): ?array
    {
        if (! $value) {
            return null;
        }

        return collect(explode(',', $value))
            ->map(fn ($item) => trim($item))
            ->filter()
            ->values()
            ->all();
    }
}
