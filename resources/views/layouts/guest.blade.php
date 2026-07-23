@php
    $siteInfo = $frontendSiteInfo ?? null;
    $siteName = data_get($siteInfo, 'site_name') ?: data_get($siteInfo, 'sidebar_lg_header') ?: config('app.name', 'Land Site');
    $logo = filled(data_get($siteInfo, 'logo'))
        ? asset(data_get($siteInfo, 'logo'))
        : asset('frontend/assets/images/logo.png');
    $favicon = data_get($siteInfo, 'favicon');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $siteName }}</title>
        @if(filled($favicon))
            <link rel="icon" href="{{ asset($favicon) }}">
            <link rel="shortcut icon" href="{{ asset($favicon) }}">
        @endif
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            :root {
                --auth-brand: #e03445;
                --auth-ink: #071226;
                --auth-muted: #64748b;
                --auth-line: #e5e7eb;
            }

            body {
                margin: 0;
                color: var(--auth-ink);
                font-family: Figtree, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
                background:
                    radial-gradient(circle at 16% 12%, rgba(224, 52, 69, .16), transparent 26%),
                    radial-gradient(circle at 86% 18%, rgba(20, 184, 166, .13), transparent 24%),
                    linear-gradient(135deg, #ffffff, #f3f6fb);
            }

            .auth-page {
                display: grid;
                min-height: 100vh;
                grid-template-columns: minmax(0, 1.02fr) minmax(420px, .78fr);
            }

            .auth-showcase {
                position: relative;
                display: flex;
                min-height: 100vh;
                flex-direction: column;
                justify-content: space-between;
                padding: 48px clamp(28px, 6vw, 88px);
                overflow: hidden;
                background:
                    linear-gradient(135deg, rgba(7, 18, 38, .88), rgba(7, 18, 38, .64)),
                    url("{{ asset('frontend/assets/images/card_img_6.jpg') }}") center / cover;
                color: #ffffff;
            }

            .auth-showcase::after {
                content: "";
                position: absolute;
                right: -120px;
                bottom: -160px;
                width: 360px;
                height: 360px;
                border-radius: 999px;
                background: rgba(224, 52, 69, .32);
                filter: blur(8px);
            }

            .auth-logo-link {
                position: relative;
                z-index: 1;
                display: inline-flex;
                width: fit-content;
                align-items: center;
                text-decoration: none;
            }

            .auth-logo {
                width: 148px;
                height: 48px;
                object-fit: contain;
                object-position: left center;
            }

            .auth-copy {
                position: relative;
                z-index: 1;
                max-width: 580px;
            }

            .auth-eyebrow {
                margin: 0 0 16px;
                color: #fca5b0;
                font-size: .8rem;
                font-weight: 900;
                letter-spacing: .12em;
                text-transform: uppercase;
            }

            .auth-copy h1 {
                margin: 0;
                font-size: clamp(2.3rem, 5vw, 4.45rem);
                line-height: .98;
                font-weight: 900;
                letter-spacing: 0;
            }

            .auth-copy p {
                max-width: 520px;
                margin: 22px 0 0;
                color: rgba(255, 255, 255, .82);
                font-size: 1.05rem;
                line-height: 1.75;
            }

            .auth-stat-row {
                position: relative;
                z-index: 1;
                display: grid;
                max-width: 560px;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 12px;
            }

            .auth-stat-row div {
                padding: 18px;
                border: 1px solid rgba(255, 255, 255, .18);
                border-radius: 22px;
                background: rgba(255, 255, 255, .1);
                backdrop-filter: blur(16px);
            }

            .auth-stat-row strong,
            .auth-stat-row span {
                display: block;
            }

            .auth-stat-row strong {
                font-size: 1.35rem;
                font-weight: 900;
            }

            .auth-stat-row span {
                margin-top: 4px;
                color: rgba(255, 255, 255, .72);
                font-size: .82rem;
                font-weight: 700;
            }

            .auth-form-panel {
                display: flex;
                min-height: 100vh;
                align-items: center;
                justify-content: center;
                padding: 42px 24px;
            }

            .auth-card {
                width: min(100%, 482px);
                padding: clamp(28px, 4vw, 42px);
                border: 1px solid rgba(7, 18, 38, .08);
                border-radius: 30px;
                background: rgba(255, 255, 255, .9);
                box-shadow: 0 28px 70px rgba(15, 23, 42, .14);
                backdrop-filter: blur(18px);
            }

            .auth-form-head {
                margin-bottom: 26px;
            }

            .auth-form-head a {
                color: var(--auth-brand);
                font-size: .86rem;
                font-weight: 900;
                text-decoration: none;
            }

            .auth-form-head h2 {
                margin: 10px 0 8px;
                color: var(--auth-ink);
                font-size: clamp(1.8rem, 4vw, 2.45rem);
                line-height: 1.08;
                font-weight: 900;
                letter-spacing: 0;
            }

            .auth-form-head p,
            .auth-helper,
            .auth-switch {
                color: var(--auth-muted);
                font-weight: 650;
            }

            .auth-form-grid {
                display: grid;
                gap: 18px;
            }

            .auth-label {
                display: block;
                margin-bottom: 8px;
                color: #334155;
                font-size: .9rem;
                font-weight: 800;
            }

            .auth-input {
                width: 100%;
                min-height: 54px;
                padding: 0 16px;
                border: 1px solid #d8dee8;
                border-radius: 16px;
                background: #ffffff;
                color: var(--auth-ink);
                font-weight: 700;
                transition: border-color .2s ease, box-shadow .2s ease;
            }

            .auth-input:focus {
                border-color: var(--auth-brand);
                box-shadow: 0 0 0 4px rgba(224, 52, 69, .12);
                outline: 0;
            }

            .auth-options {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                flex-wrap: wrap;
            }

            .auth-checkbox {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                color: #475569;
                font-weight: 750;
            }

            .auth-checkbox input {
                width: 18px;
                height: 18px;
                border-radius: 6px;
                accent-color: var(--auth-brand);
            }

            .auth-link {
                color: var(--auth-ink);
                font-weight: 850;
                text-decoration: none;
            }

            .auth-link:hover,
            .auth-link:focus {
                color: var(--auth-brand);
            }

            .auth-actions {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                margin-top: 24px;
            }

            .auth-submit {
                display: inline-flex;
                min-height: 52px;
                align-items: center;
                justify-content: center;
                padding: 0 24px;
                border: 0;
                border-radius: 16px;
                background: linear-gradient(135deg, var(--auth-brand), #b91c2b);
                color: #ffffff;
                font-weight: 900;
                box-shadow: 0 18px 34px rgba(224, 52, 69, .25);
                transition: transform .2s ease, box-shadow .2s ease;
            }

            .auth-submit:hover,
            .auth-submit:focus {
                transform: translateY(-1px);
                box-shadow: 0 22px 42px rgba(224, 52, 69, .32);
            }

            .auth-status {
                margin-bottom: 18px;
                padding: 12px 14px;
                border: 1px solid #bbf7d0;
                border-radius: 14px;
                background: #f0fdf4;
                color: #166534;
                font-weight: 800;
            }

            @media (max-width: 991.98px) {
                .auth-page {
                    display: block;
                }

                .auth-showcase {
                    min-height: auto;
                    padding: 28px 22px 34px;
                    border-bottom-left-radius: 28px;
                    border-bottom-right-radius: 28px;
                }

                .auth-copy {
                    margin-top: 44px;
                }

                .auth-stat-row {
                    margin-top: 34px;
                }

                .auth-form-panel {
                    min-height: auto;
                    padding: 28px 18px 42px;
                }
            }

            @media (max-width: 575.98px) {
                .auth-showcase {
                    padding: 22px 16px 28px;
                }

                .auth-logo {
                    width: 126px;
                    height: 42px;
                }

                .auth-copy h1 {
                    font-size: 2.2rem;
                }

                .auth-stat-row {
                    grid-template-columns: 1fr;
                }

                .auth-card {
                    padding: 24px 18px;
                    border-radius: 24px;
                }

                .auth-actions {
                    align-items: stretch;
                    flex-direction: column-reverse;
                }

                .auth-submit {
                    width: 100%;
                }
            }
        </style>
    </head>
    <body>
        <main class="auth-page">
            <section class="auth-showcase">
                <a class="auth-logo-link" href="{{ route('frontend.home') }}">
                    <img class="auth-logo" src="{{ $logo }}" alt="{{ $siteName }} logo">
                </a>

                <div class="auth-copy">
                    <p class="auth-eyebrow">Property workspace</p>
                    <h1>Search, save, and manage homes with confidence.</h1>
                    <p>Access saved properties, listing tools, subscriptions, and property activity from one secure account.</p>
                </div>

                <div class="auth-stat-row">
                    <div>
                        <strong>Verified</strong>
                        <span>Property data</span>
                    </div>
                    <div>
                        <strong>Secure</strong>
                        <span>User access</span>
                    </div>
                    <div>
                        <strong>Fast</strong>
                        <span>Listing workflow</span>
                    </div>
                </div>
            </section>

            <section class="auth-form-panel">
                <div class="auth-card">
                    {{ $slot }}
                </div>
            </section>
        </main>
    </body>
</html>
