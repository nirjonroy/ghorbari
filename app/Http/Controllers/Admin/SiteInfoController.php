<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteInfo;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteInfoController extends Controller
{
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
            'logo_width' => ['nullable', 'integer', 'min:1', 'max:5000'],
            'logo_height' => ['nullable', 'integer', 'min:1', 'max:5000'],
            'favicon_width' => ['nullable', 'integer', 'min:1', 'max:512'],
            'favicon_height' => ['nullable', 'integer', 'min:1', 'max:512'],
            'image_output_format' => ['required', 'in:jpg,png,webp'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'text_direction' => ['required', 'in:ltr,rtl'],
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
        ]);

        foreach ([
            'maintenance_mode',
            'enable_user_register',
            'phone_number_required',
            'enable_subscription_notify',
            'enable_save_contact_message',
        ] as $field) {
            $data[$field] = $request->boolean($field);
        }

        unset($data['logo'], $data['favicon']);

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

        SiteInfo::query()->updateOrCreate(
            ['id' => optional($siteInfo)->id],
            $data
        );

        return redirect()
            ->route('admin.site-info.index')
            ->with('status', 'Site info updated successfully.');
    }
}
