<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesSeoFields;
use App\Http\Controllers\Controller;
use App\Models\SiteInfo;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SiteInfoController extends Controller
{
    use ManagesSeoFields;

    public function index(): View
    {
        $siteInfo = SiteInfo::query()->first();

        return view('Admin.site_info.index', compact('siteInfo'));
    }

    public function edit(): View
    {
        $siteInfo = SiteInfo::query()->first();

        return view('Admin.site_info.edit', compact('siteInfo'));
    }

    public function update(Request $request): RedirectResponse
    {
        $siteInfo = SiteInfo::query()->first();

        $data = $request->validate([
            'google_location' => ['nullable', 'string'],
            'footer_google_location' => ['nullable', 'string'],
            'footer_contact_note' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'favicon' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1024'],
            'buy_home_icon' => ['nullable', 'file', 'mimes:svg,jpg,jpeg,png,webp', 'max:1024'],
            'sell_home_icon' => ['nullable', 'file', 'mimes:svg,jpg,jpeg,png,webp', 'max:1024'],
            'rent_property_icon' => ['nullable', 'file', 'mimes:svg,jpg,jpeg,png,webp', 'max:1024'],
            'calculator_price_min' => ['required', 'integer', 'min:1'],
            'calculator_price_max' => ['required', 'integer', 'gte:calculator_price_min'],
            'calculator_price_step' => ['required', 'integer', 'min:1'],
            'calculator_default_price' => ['required', 'integer', 'gte:calculator_price_min', 'lte:calculator_price_max'],
            'calculator_down_percent_min' => ['required', 'integer', 'min:0', 'max:100'],
            'calculator_down_percent_max' => ['required', 'integer', 'gte:calculator_down_percent_min', 'max:100'],
            'calculator_default_down_percent' => ['required', 'integer', 'gte:calculator_down_percent_min', 'lte:calculator_down_percent_max'],
            'calculator_loan_year_min' => ['required', 'integer', 'min:1', 'max:60'],
            'calculator_loan_year_max' => ['required', 'integer', 'gte:calculator_loan_year_min', 'max:60'],
            'calculator_default_loan_years' => ['required', 'integer', 'gte:calculator_loan_year_min', 'lte:calculator_loan_year_max'],
            'calculator_interest_min' => ['required', 'numeric', 'min:0', 'max:100'],
            'calculator_interest_max' => ['required', 'numeric', 'gte:calculator_interest_min', 'max:100'],
            'calculator_default_interest_rate' => ['required', 'numeric', 'gte:calculator_interest_min', 'lte:calculator_interest_max'],
            'calculator_tax_min' => ['required', 'numeric', 'min:0', 'max:100'],
            'calculator_tax_max' => ['required', 'numeric', 'gte:calculator_tax_min', 'max:100'],
            'calculator_default_tax_rate' => ['required', 'numeric', 'gte:calculator_tax_min', 'lte:calculator_tax_max'],
            'calculator_service_charge_min' => ['required', 'integer', 'min:0'],
            'calculator_service_charge_max' => ['required', 'integer', 'gte:calculator_service_charge_min'],
            'calculator_default_service_charge' => ['required', 'integer', 'gte:calculator_service_charge_min', 'lte:calculator_service_charge_max'],
            'calculator_service_charge_step' => ['required', 'integer', 'min:1'],
            'logo_width' => ['nullable', 'integer', 'min:1', 'max:5000'],
            'logo_height' => ['nullable', 'integer', 'min:1', 'max:5000'],
            'favicon_width' => ['nullable', 'integer', 'min:1', 'max:512'],
            'favicon_height' => ['nullable', 'integer', 'min:1', 'max:512'],
            'image_output_format' => ['required', 'in:jpg,png,webp,original'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'text_direction' => ['required', 'in:ltr,rtl'],
            'default_theme' => ['required', 'in:light,dark'],
            'timezone' => ['required', 'string', 'max:255'],
            'sidebar_lg_header' => ['nullable', 'string', 'max:255'],
            'sidebar_sm_header' => ['nullable', 'string', 'max:255'],
            'topbar_phone' => ['nullable', 'string', 'max:255'],
            'topbar_email' => ['nullable', 'email', 'max:255'],
            'currency_name' => ['nullable', 'string', 'max:255'],
            'currency_icon' => ['nullable', 'string', 'max:255'],
            'currency_rate' => ['required', 'numeric', 'min:0'],
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
        ] + $this->seoValidationRules());

        foreach ([
            'maintenance_mode',
            'enable_user_register',
            'phone_number_required',
            'enable_subscription_notify',
            'enable_save_contact_message',
        ] as $field) {
            $data[$field] = $request->boolean($field);
        }

        unset($data['logo'], $data['favicon'], $data['buy_home_icon'], $data['sell_home_icon'], $data['rent_property_icon'], $data['meta_image']);

        $imageUploadService = new ImageUploadService();

        foreach (['logo', 'favicon'] as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $imageUploadService->storeConverted(
                    $request->file($field),
                    'uploads/siteinfo',
                    $data[$field.'_width'] ?? null,
                    $data[$field.'_height'] ?? null,
                    $siteInfo?->{$field},
                    $data['image_output_format']
                );
            }
        }

        foreach (['buy_home_icon', 'sell_home_icon', 'rent_property_icon'] as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $this->storeServiceIcon(
                    $request->file($field),
                    $siteInfo?->{$field},
                    $imageUploadService,
                    $data['image_output_format']
                );
            }
        }
        $data = $this->applySeoImage($request, $data, $siteInfo, 'uploads/siteinfo/seo');

        SiteInfo::query()->updateOrCreate(
            ['id' => optional($siteInfo)->id],
            $data
        );

        return redirect()
            ->route('admin.site-info.index')
            ->with('status', 'Site info updated successfully.');
    }

    private function storeServiceIcon(
        UploadedFile $file,
        ?string $oldPath,
        ImageUploadService $imageUploadService,
        string $format
    ): string {
        if (strtolower($file->getClientOriginalExtension()) !== 'svg') {
            return $imageUploadService->storeConverted($file, 'uploads/siteinfo', null, null, $oldPath, $format);
        }

        $directory = 'uploads/siteinfo';
        $targetDirectory = public_path($directory);

        if (! File::isDirectory($targetDirectory)) {
            File::makeDirectory($targetDirectory, 0755, true);
        }

        if ($oldPath && File::exists(public_path($oldPath))) {
            File::delete(public_path($oldPath));
        }

        $baseName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'icon';
        $fileName = $baseName.'.svg';
        $counter = 2;

        while (File::exists($targetDirectory.DIRECTORY_SEPARATOR.$fileName)) {
            $fileName = $baseName.'-'.$counter.'.svg';
            $counter++;
        }

        $file->move($targetDirectory, $fileName);

        return $directory.'/'.$fileName;
    }
}
