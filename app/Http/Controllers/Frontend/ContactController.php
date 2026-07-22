<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\SiteInfo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        return view('Frontend.contact.index', [
            'contactData' => $this->contactData(),
        ]);
    }

    public function api(): JsonResponse
    {
        return response()->json([
            'data' => $this->contactData(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:40'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $siteInfo = Schema::hasTable('siteinfo') ? SiteInfo::query()->first() : null;

        if (! $siteInfo || $siteInfo->enable_save_contact_message) {
            ContactMessage::create(array_merge($data, [
                'user_id' => Auth::id(),
                'message_type' => 'contact_page',
                'source_page' => route('frontend.contact.index'),
                'ip_address' => $request->ip(),
                'user_agent' => (string) $request->userAgent(),
                'status' => 'new',
            ]));
        }

        return back()->with('status', 'Your message has been sent successfully.');
    }

    private function contactData(): array
    {
        $siteInfo = Schema::hasTable('siteinfo') ? SiteInfo::query()->first() : null;

        return [
            'site_info' => $siteInfo,
            'contact_cards' => [
                [
                    'icon' => 'bi-envelope',
                    'label' => 'Email',
                    'value' => $siteInfo?->contact_email ?: $siteInfo?->topbar_email ?: 'support@landsite.test',
                    'href' => 'mailto:'.($siteInfo?->contact_email ?: $siteInfo?->topbar_email ?: 'support@landsite.test'),
                ],
                [
                    'icon' => 'bi-telephone',
                    'label' => 'Phone',
                    'value' => $siteInfo?->topbar_phone ?: 'Not added',
                    'href' => $siteInfo?->topbar_phone ? 'tel:'.$siteInfo->topbar_phone : null,
                ],
                [
                    'icon' => 'bi-geo-alt',
                    'label' => 'Location',
                    'value' => $siteInfo?->footer_contact_note ?: 'Bangladesh property support desk',
                    'href' => null,
                ],
            ],
            'map' => $siteInfo?->footer_google_location ?: $siteInfo?->google_location,
            'api_url' => route('api.frontend.contact'),
        ];
    }
}
