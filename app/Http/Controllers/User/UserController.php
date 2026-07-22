<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ContactMessage;
use App\Models\Favorite;
use App\Models\OpenHouseSchedule;
use App\Models\Property;
use App\Models\PropertyView;
use App\Models\SavedSearch;
use App\Models\SubscriptionPackage;
use App\Models\SubscriptionPayment;
use App\Models\UserNotification;
use App\Models\UserSubscription;
use App\Services\SslCommerzService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function dashboard(Request $request): View
    {
        return view('User.dashboard', [
            'dashboardData' => $this->dashboardData($request),
        ]);
    }

    public function profile(Request $request): View
    {
        return view('User.profile', [
            'dashboardData' => $this->dashboardData($request),
        ]);
    }

    public function edit(Request $request): View
    {
        return view('User.edit', [
            'dashboardData' => $this->dashboardData($request),
        ]);
    }

    public function subscriptions(Request $request): View
    {
        return view('User.subscriptions.index', [
            'dashboardData' => $this->dashboardData($request),
            'packages' => $this->tableExists(SubscriptionPackage::class)
                ? SubscriptionPackage::query()->where('is_active', true)->orderBy('sort_order')->orderBy('price')->get()
                : collect(),
            'activeSubscription' => $this->activeSubscription($request->user()->id),
            'payments' => $this->tableExists(SubscriptionPayment::class)
                ? SubscriptionPayment::query()->where('user_id', $request->user()->id)->latest()->take(10)->get()
                : collect(),
        ]);
    }

    public function billings(Request $request): View
    {
        return view('User.billings.index', [
            'dashboardData' => $this->dashboardData($request),
            'subscriptions' => $this->tableExists(UserSubscription::class)
                ? UserSubscription::query()
                    ->where('user_id', $request->user()->id)
                    ->with('package:id,name,slug')
                    ->latest()
                    ->paginate(10)
                : collect(),
            'payments' => $this->tableExists(SubscriptionPayment::class)
                ? SubscriptionPayment::query()
                    ->where('user_id', $request->user()->id)
                    ->with(['package:id,name,slug', 'subscription:id,package_name,status'])
                    ->latest()
                    ->paginate(10)
                : collect(),
            'activeSubscription' => $this->activeSubscription($request->user()->id),
        ]);
    }

    public function addPayment(Request $request): View
    {
        return view('User.billings.add-payment', [
            'dashboardData' => $this->dashboardData($request),
            'packages' => $this->tableExists(SubscriptionPackage::class)
                ? SubscriptionPackage::query()->where('is_active', true)->orderBy('sort_order')->orderBy('price')->get()
                : collect(),
            'activeSubscription' => $this->activeSubscription($request->user()->id),
        ]);
    }

    public function activityLogs(Request $request): View
    {
        $userId = $request->user()->id;
        $activities = collect();

        if ($this->tableExists(Property::class)) {
            $properties = Property::query()
                ->where('owner_user_id', $userId)
                ->select('id', 'title', 'verification_status', 'is_published', 'created_at', 'updated_at')
                ->latest('updated_at')
                ->take(20)
                ->get();

            foreach ($properties as $property) {
                $activities->push([
                    'title' => 'Property updated',
                    'description' => $property->title.' is '.$property->verification_status.($property->is_published ? ' and published.' : '.'),
                    'icon' => 'bi-house-check',
                    'time' => $property->updated_at,
                ]);
            }
        }

        if ($this->tableExists(UserSubscription::class)) {
            $subscriptions = UserSubscription::query()
                ->where('user_id', $userId)
                ->select('id', 'package_name', 'status', 'starts_at', 'ends_at', 'created_at', 'updated_at')
                ->latest('updated_at')
                ->take(20)
                ->get();

            foreach ($subscriptions as $subscription) {
                $activities->push([
                    'title' => 'Subscription '.$subscription->status,
                    'description' => $subscription->package_name.' package'.($subscription->ends_at ? ' ends '.$subscription->ends_at->format('d M Y').'.' : '.'),
                    'icon' => 'bi-gem',
                    'time' => $subscription->updated_at,
                ]);
            }
        }

        if ($this->tableExists(SubscriptionPayment::class)) {
            $payments = SubscriptionPayment::query()
                ->where('user_id', $userId)
                ->select('id', 'transaction_id', 'amount', 'currency', 'status', 'created_at', 'updated_at')
                ->latest('updated_at')
                ->take(20)
                ->get();

            foreach ($payments as $payment) {
                $activities->push([
                    'title' => 'Payment '.$payment->status,
                    'description' => $payment->transaction_id.' - '.$payment->currency.' '.number_format((float) $payment->amount, 2),
                    'icon' => 'bi-receipt',
                    'time' => $payment->updated_at,
                ]);
            }
        }

        return view('User.activity-logs.index', [
            'dashboardData' => $this->dashboardData($request),
            'activities' => $activities
                ->filter(fn ($activity) => $activity['time'])
                ->sortByDesc('time')
                ->take(50)
                ->values(),
        ]);
    }

    public function appointments(Request $request): View
    {
        return view('User.appointments.index', [
            'dashboardData' => $this->dashboardData($request),
            'appointments' => $this->tableExists(Appointment::class)
                ? Appointment::query()
                    ->where('user_id', $request->user()->id)
                    ->with(['property:id,title,slug,price,listing_type'])
                    ->latest('scheduled_at')
                    ->paginate(12)
                : collect(),
        ]);
    }

    public function favorites(Request $request): View
    {
        return view('User.favorites.index', [
            'dashboardData' => $this->dashboardData($request),
            'favorites' => $this->tableExists(Favorite::class)
                ? Favorite::query()
                    ->where('user_id', $request->user()->id)
                    ->with(['property.media', 'property.district:id,name', 'property.city:id,name', 'property.area:id,name'])
                    ->latest()
                    ->paginate(12)
                : collect(),
        ]);
    }

    public function savedSearches(Request $request): View
    {
        return view('User.saved-searches.index', [
            'dashboardData' => $this->dashboardData($request),
            'savedSearches' => $this->tableExists(SavedSearch::class)
                ? SavedSearch::query()->where('user_id', $request->user()->id)->latest()->paginate(12)
                : collect(),
        ]);
    }

    public function notifications(Request $request): View
    {
        return view('User.notifications.index', [
            'dashboardData' => $this->dashboardData($request),
            'notifications' => $this->tableExists(UserNotification::class)
                ? UserNotification::query()->where('user_id', $request->user()->id)->latest()->paginate(15)
                : collect(),
        ]);
    }

    public function openHouse(Request $request): View
    {
        $propertyQuery = $this->tableExists(Property::class)
            ? Property::query()
                ->where('owner_user_id', $request->user()->id)
                ->where('is_open_house', true)
                ->with(['district:id,name', 'city:id,name', 'area:id,name'])
                ->latest()
            : null;

        return view('User.open-house.index', [
            'dashboardData' => $this->dashboardData($request),
            'openHouseProperties' => $propertyQuery ? $propertyQuery->paginate(12) : collect(),
            'schedules' => $this->tableExists(OpenHouseSchedule::class)
                ? OpenHouseSchedule::query()
                    ->whereHas('property', fn ($query) => $query->where('owner_user_id', $request->user()->id))
                    ->with('property:id,title,slug')
                    ->latest('starts_at')
                    ->take(10)
                    ->get()
                : collect(),
        ]);
    }

    public function feed(Request $request): View
    {
        return view('User.feed.index', [
            'dashboardData' => $this->dashboardData($request),
            'properties' => $this->tableExists(Property::class)
                ? Property::query()
                    ->where('is_published', true)
                    ->where('verification_status', 'approved')
                    ->with(['type:id,name,slug', 'media', 'district:id,name', 'city:id,name', 'area:id,name'])
                    ->latest('published_at')
                    ->take(20)
                    ->get()
                : collect(),
        ]);
    }

    public function checkout(Request $request, SubscriptionPackage $package, SslCommerzService $sslCommerz): RedirectResponse
    {
        abort_unless($package->is_active, 404);

        $user = $request->user();
        $transactionId = 'SUB-'.$user->id.'-'.now()->format('YmdHis').'-'.Str::upper(Str::random(6));

        $subscription = UserSubscription::create([
            'user_id' => $user->id,
            'subscription_package_id' => $package->id,
            'package_name' => $package->name,
            'price' => $package->price,
            'currency' => $package->currency,
            'duration_days' => $package->duration_days,
            'property_limit' => $package->property_limit,
            'featured_property_limit' => $package->featured_property_limit,
            'status' => 'pending',
        ]);

        $payment = SubscriptionPayment::create([
            'user_id' => $user->id,
            'subscription_package_id' => $package->id,
            'user_subscription_id' => $subscription->id,
            'transaction_id' => $transactionId,
            'amount' => $package->price,
            'currency' => $package->currency,
            'status' => 'pending',
        ]);

        try {
            $gateway = $sslCommerz->initiate([
                'total_amount' => $payment->amount,
                'currency' => $payment->currency,
                'tran_id' => $payment->transaction_id,
                'success_url' => route('user.subscriptions.payment.success'),
                'fail_url' => route('user.subscriptions.payment.fail'),
                'cancel_url' => route('user.subscriptions.payment.cancel'),
                'ipn_url' => route('user.subscriptions.payment.ipn'),
                'cus_name' => $user->name,
                'cus_email' => $user->email,
                'cus_add1' => $user->present_address ?: 'Bangladesh',
                'cus_city' => $user->district ?: 'Dhaka',
                'cus_country' => $user->country ?: 'Bangladesh',
                'cus_phone' => $user->phone ?: '01700000000',
                'product_name' => $package->name,
                'product_category' => 'Subscription',
            ]);
        } catch (\Throwable $exception) {
            $payment->update([
                'status' => 'failed',
                'gateway_response' => ['error' => $exception->getMessage()],
            ]);
            $subscription->update(['status' => 'failed']);

            return redirect()
                ->route('user.subscriptions.index')
                ->with('error', $exception->getMessage());
        }

        $payment->update(['gateway_response' => $gateway]);

        return redirect()->away($gateway['GatewayPageURL']);
    }

    public function paymentSuccess(Request $request, SslCommerzService $sslCommerz): RedirectResponse
    {
        $paid = $this->markPayment($request, 'paid', $sslCommerz);

        return redirect()
            ->route('user.subscriptions.index')
            ->with($paid ? 'status' : 'error', $paid ? 'Subscription payment completed successfully.' : 'Payment validation failed.');
    }

    public function paymentFail(Request $request): RedirectResponse
    {
        $this->markPayment($request, 'failed');

        return redirect()
            ->route('user.subscriptions.index')
            ->with('error', 'Subscription payment failed.');
    }

    public function paymentCancel(Request $request): RedirectResponse
    {
        $this->markPayment($request, 'cancelled');

        return redirect()
            ->route('user.subscriptions.index')
            ->with('error', 'Subscription payment was cancelled.');
    }

    public function paymentIpn(Request $request, SslCommerzService $sslCommerz)
    {
        $this->markPayment($request, in_array($request->input('status'), ['VALID', 'VALIDATED'], true) ? 'paid' : 'failed', $sslCommerz);

        return response('OK');
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'account_type' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
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
            'nid_number' => ['nullable', 'string', 'max:255'],
            'passport_number' => ['nullable', 'string', 'max:255'],
            'ownership_document_type' => ['nullable', 'string', 'max:255'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:255'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'nid_front_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'nid_back_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'passport_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'ownership_proof' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
            'home_elevation_images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        foreach ([
            'profile_photo' => 'profile_photo_path',
            'nid_front_image' => 'nid_front_image_path',
            'nid_back_image' => 'nid_back_image_path',
            'passport_image' => 'passport_image_path',
            'ownership_proof' => 'ownership_proof_path',
        ] as $input => $column) {
            if ($request->hasFile($input)) {
                $validated[$column] = $this->storeUserFile($request->file($input), $input);
            }
        }

        if ($request->hasFile('home_elevation_images')) {
            $existingImages = collect($user->home_elevation_image_paths ?? []);
            $newImages = collect($request->file('home_elevation_images'))
                ->map(fn ($file) => $this->storeUserFile($file, 'home_elevation'));

            $validated['home_elevation_image_paths'] = $existingImages->merge($newImages)->values()->all();
        }

        unset(
            $validated['profile_photo'],
            $validated['nid_front_image'],
            $validated['nid_back_image'],
            $validated['passport_image'],
            $validated['ownership_proof'],
            $validated['home_elevation_images']
        );

        $user->update($validated);

        return redirect()
            ->route('dashboard')
            ->with('status', 'Profile updated successfully.');
    }

    private function dashboardData(Request $request): array
    {
        $user = $request->user();
        $propertyIds = $this->tableExists(Property::class)
            ? Property::query()->where('owner_user_id', $user->id)->pluck('id')
            : collect();

        $propertyQuery = $this->tableExists(Property::class)
            ? Property::query()->where('owner_user_id', $user->id)
            : null;

        $recentProperties = $propertyQuery
            ? (clone $propertyQuery)
                ->select('id', 'property_type_id', 'title', 'slug', 'listing_type', 'property_status', 'price', 'rent_period', 'verification_status', 'is_published', 'created_at')
                ->with([
                    'type:id,name,slug,icon',
                    'media:id,property_id,media_type,file_path,alt_text,is_primary,sort_order',
                ])
                ->latest()
                ->take(3)
                ->get()
            : collect();

        return [
            'user' => $user,
            'account_type' => $this->accountTypeLabel($user->account_type),
            'profile_completion' => $this->profileCompletion($user),
            'stats' => [
                'properties' => $propertyQuery ? (clone $propertyQuery)->count() : 0,
                'published' => $propertyQuery ? (clone $propertyQuery)->where('is_published', true)->count() : 0,
                'inquiries' => $this->tableExists(ContactMessage::class)
                    ? ContactMessage::query()->where('user_id', $user->id)->count()
                    : 0,
                'pending' => $propertyQuery ? (clone $propertyQuery)->where('verification_status', 'pending')->count() : 0,
                'views' => $this->propertyViewsCount($propertyIds),
            ],
            'recent_properties' => $recentProperties,
            'recent_messages' => $this->recentMessages($user->id),
            'active_subscription' => $this->activeSubscription($user->id),
        ];
    }

    private function propertyViewsCount($propertyIds): int
    {
        if (! $this->tableExists(PropertyView::class) || $propertyIds->isEmpty()) {
            return 0;
        }

        return PropertyView::query()->whereIn('property_id', $propertyIds)->count();
    }

    private function recentMessages(int $userId)
    {
        if (! $this->tableExists(ContactMessage::class)) {
            return collect();
        }

        return ContactMessage::query()
            ->select('id', 'subject', 'message', 'status', 'created_at')
            ->where('user_id', $userId)
            ->latest()
            ->take(2)
            ->get();
    }

    private function profileCompletion($user): int
    {
        $fields = [
            'name',
            'email',
            'phone',
            'account_type',
            'date_of_birth',
            'gender',
            'profession',
            'present_address',
            'district',
            'division',
            'profile_photo_path',
            'nid_number',
        ];

        $completed = collect($fields)->filter(fn ($field) => filled($user->{$field}))->count();

        return (int) round(($completed / count($fields)) * 100);
    }

    private function accountTypeLabel(?string $accountType): string
    {
        if (! $accountType) {
            return 'User Account';
        }

        return ucwords(str_replace(['_', '-'], ' ', $accountType)).' Account';
    }

    private function tableExists(string $model): bool
    {
        return Schema::hasTable((new $model())->getTable());
    }

    private function activeSubscription(int $userId): ?UserSubscription
    {
        if (! $this->tableExists(UserSubscription::class)) {
            return null;
        }

        return UserSubscription::query()
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->latest('ends_at')
            ->first();
    }

    private function markPayment(Request $request, string $status, ?SslCommerzService $sslCommerz = null): bool
    {
        $transactionId = $request->input('tran_id');

        if (! $transactionId || ! $this->tableExists(SubscriptionPayment::class)) {
            return false;
        }

        $payment = SubscriptionPayment::query()
            ->where('transaction_id', $transactionId)
            ->with('subscription')
            ->first();

        if (! $payment) {
            return false;
        }

        $validation = null;

        if ($status === 'paid') {
            try {
                $validation = $sslCommerz && $request->filled('val_id')
                    ? $sslCommerz->validate($request->input('val_id'))
                    : null;
            } catch (\Throwable $exception) {
                $validation = ['status' => 'FAILED', 'error' => $exception->getMessage()];
            }

            if (! in_array($validation['status'] ?? null, ['VALID', 'VALIDATED'], true)) {
                $status = 'failed';
            }
        }

        $payment->update([
            'status' => $status,
            'ssl_transaction_id' => $request->input('val_id') ?: $request->input('bank_tran_id'),
            'payment_method' => $request->input('card_issuer') ?: $request->input('card_type'),
            'gateway_response' => array_merge($payment->gateway_response ?? [], [
                'callback' => $request->all(),
                'validation' => $validation,
            ]),
            'paid_at' => $status === 'paid' ? now() : $payment->paid_at,
        ]);

        if (! $payment->subscription) {
            return $status === 'paid';
        }

        if ($status === 'paid') {
            $startsAt = now();
            $payment->subscription->update([
                'starts_at' => $startsAt,
                'ends_at' => $startsAt->copy()->addDays($payment->subscription->duration_days),
                'status' => 'active',
            ]);

            UserSubscription::query()
                ->where('user_id', $payment->user_id)
                ->where('id', '!=', $payment->subscription->id)
                ->where('status', 'active')
                ->update(['status' => 'expired']);

            return true;
        }

        $payment->subscription->update(['status' => $status]);

        return false;
    }

    private function storeUserFile($file, string $prefix): string
    {
        $directory = public_path('uploads/users');

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $filename = $prefix.'-'.auth()->id().'-'.time().'-'.uniqid().'.'.$file->getClientOriginalExtension();
        $file->move($directory, $filename);

        return 'uploads/users/'.$filename;
    }
}
