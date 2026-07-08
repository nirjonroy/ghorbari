<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteInfo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'favicon' => ['nullable', 'file', 'mimes:ico,jpg,jpeg,png,webp,svg', 'max:1024'],
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

        foreach (['logo', 'favicon'] as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $this->storeImage($request, $field, $siteInfo?->{$field});
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

    private function storeImage(Request $request, string $field, ?string $oldPath): string
    {
        $directory = public_path('uploads/siteinfo');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        if ($oldPath && File::exists(public_path($oldPath))) {
            File::delete(public_path($oldPath));
        }

        $file = $request->file($field);
        $filename = $field.'-'.time().'-'.uniqid().'.'.$file->getClientOriginalExtension();

        $file->move($directory, $filename);

        return 'uploads/siteinfo/'.$filename;
    }
}
