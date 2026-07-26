@php
    $siteName = data_get($siteInfo, 'site_name') ?: data_get($siteInfo, 'sidebar_lg_header') ?: config('app.name', 'Land Site');
    $logo = filled(data_get($siteInfo, 'logo')) ? asset(data_get($siteInfo, 'logo')) : asset('frontend/assets/images/logo.png');
    $email = data_get($siteInfo, 'contact_email') ?: data_get($siteInfo, 'topbar_email');
    $phone = data_get($siteInfo, 'topbar_phone');
@endphp
<!doctype html>
<html lang="en" dir="{{ data_get($siteInfo, 'text_direction', 'ltr') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Maintenance | {{ $siteName }}</title>
    @if(filled(data_get($siteInfo, 'favicon')))
        <link rel="icon" href="{{ asset(data_get($siteInfo, 'favicon')) }}">
    @endif
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: #f6f7fb;
            color: #071226;
            font-family: Arial, sans-serif;
        }
        .maintenance-card {
            width: min(92vw, 560px);
            padding: 42px;
            border: 1px solid #e5e7eb;
            border-radius: 24px;
            background: #fff;
            box-shadow: 0 24px 70px rgba(15, 23, 42, .12);
            text-align: center;
        }
        img {
            max-width: 160px;
            max-height: 72px;
            object-fit: contain;
            margin-bottom: 24px;
        }
        h1 {
            margin: 0 0 12px;
            font-size: 34px;
            line-height: 1.1;
        }
        p {
            margin: 0;
            color: #64748b;
            line-height: 1.7;
        }
        .maintenance-contact {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 24px;
        }
        a {
            color: #e03445;
            font-weight: 700;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <main class="maintenance-card">
        <img src="{{ $logo }}" alt="{{ $siteName }} logo">
        <h1>We will be back soon</h1>
        <p>{{ $siteName }} is currently under maintenance. Please check again later.</p>
        @if($email || $phone)
            <div class="maintenance-contact">
                @if($email)<a href="mailto:{{ $email }}">{{ $email }}</a>@endif
                @if($phone)<a href="tel:{{ $phone }}">{{ $phone }}</a>@endif
            </div>
        @endif
    </main>
</body>
</html>
