<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPackage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SubscriptionPackageController extends Controller
{
    public function index(): View
    {
        $packages = SubscriptionPackage::query()
            ->orderBy('sort_order')
            ->orderBy('price')
            ->paginate(15);

        return view('Admin.subscription_packages.index', compact('packages'));
    }

    public function create(): View
    {
        return view('Admin.subscription_packages.create', ['package' => new SubscriptionPackage()]);
    }

    public function store(Request $request): RedirectResponse
    {
        SubscriptionPackage::create($this->validatedData($request));

        return redirect()
            ->route('admin.subscription-packages.index')
            ->with('status', 'Subscription package created successfully.');
    }

    public function edit(SubscriptionPackage $subscriptionPackage): View
    {
        return view('Admin.subscription_packages.edit', ['package' => $subscriptionPackage]);
    }

    public function update(Request $request, SubscriptionPackage $subscriptionPackage): RedirectResponse
    {
        $subscriptionPackage->update($this->validatedData($request, $subscriptionPackage));

        return redirect()
            ->route('admin.subscription-packages.index')
            ->with('status', 'Subscription package updated successfully.');
    }

    public function destroy(SubscriptionPackage $subscriptionPackage): RedirectResponse
    {
        $subscriptionPackage->delete();

        return redirect()
            ->route('admin.subscription-packages.index')
            ->with('status', 'Subscription package deleted successfully.');
    }

    private function validatedData(Request $request, ?SubscriptionPackage $package = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('subscription_packages', 'slug')->ignore($package)],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:10'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'property_limit' => ['nullable', 'integer', 'min:0'],
            'featured_property_limit' => ['nullable', 'integer', 'min:0'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['name'], $package);
        $data['currency'] = strtoupper($data['currency']);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }

    private function uniqueSlug(string $value, ?SubscriptionPackage $package = null): string
    {
        $baseSlug = Str::slug($value) ?: 'subscription-package';
        $slug = $baseSlug;
        $counter = 2;

        while (SubscriptionPackage::query()
            ->where('slug', $slug)
            ->when($package, fn ($query) => $query->where('id', '!=', $package->id))
            ->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
