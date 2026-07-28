<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SEO Modules
    |--------------------------------------------------------------------------
    |
    | Here you can enable or disable specific modules within the package.
    | Set a module to 'true' to enable it, or 'false' to disable it.
    |
    */
    'modules' => [
        'meta'         => true,
        'sitemaps'     => true,
        'redirections' => true,
        'schema'       => true,
        'local_seo'    => true,
        'image_seo'    => true,
        'minify_html'  => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Package Admin Layout
    |--------------------------------------------------------------------------
    |
    | Override these values when you want the package admin pages to render
    | inside your application's layout and content section.
    |
    */
    'layout' => 'Admin.layouts.seo',
    'section' => 'seo_content',

    /*
    |--------------------------------------------------------------------------
    | Package Admin Routes
    |--------------------------------------------------------------------------
    |
    | Middleware used by package admin pages and APIs.
    |
    */
    'admin' => [
        'middleware' => ['web', 'auth:admin'],
        'sidebar' => [
            'auto_install' => false,
            'path' => resource_path('views/Admin/partials/sidebar.blade.php'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default SEO Values
    |--------------------------------------------------------------------------
    |
    | These values are used as fallbacks when specific SEO data is not
    | provided for a given page or entity.
    |
    */
    'defaults' => [
        'site_name'       => env('APP_NAME', 'Ghorbari'),
        'title_separator' => '|',
        'author'          => '',
        'publisher'       => '',
        'copyright'       => '',
        'default_image'   => '', // Provide a URL or asset path to a default Open Graph image
    ],

    /*
    |--------------------------------------------------------------------------
    | Sitemap Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for generating XML and HTML sitemaps.
    |
    */
    'sitemap' => [
        'enable_xml' => true,
        'enable_html' => true,
        'filename' => 'sitemap.xml',
        'urls_per_file' => 1000,
        'child_pattern' => '{base}-{page}.xml',
        'exclude_urls' => ['/admin/*', '/login', '/register'],
        'change_frequency' => 'weekly',
        'default_priority' => '1.0',
        
        // Add your Eloquent model classes here (e.g., \App\Models\Product::class, \App\Models\Service::class)
        // to automatically include them in the sitemap.
        'models' => [
            \App\Models\Property::class,
            \App\Models\BlogPost::class,
            \App\Models\CustomPage::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Static SEO Files
    |--------------------------------------------------------------------------
    |
    | Content defaults for generated text files.
    |
    */
    'files' => [
        'robots_txt' => "User-agent: *\nAllow: /\nSitemap: " . env('APP_URL') . "/sitemap.xml",
        'llms_txt' => "Title: Ghorbari\nDescription: Bangladesh real estate listings and property information.\n",
        'security_txt' => "Contact: mailto:" . env('MAIL_FROM_ADDRESS', 'admin@example.com') . "\nExpires: 2027-01-01T00:00:00.000Z\n",
    ],

    /*
    |--------------------------------------------------------------------------
    | Local Business Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for Local Business Schema.
    |
    */
    'local_business' => [
        'name' => 'Placeholder Business Name',
        'image' => 'https://example.com/image.jpg',
        'telephone' => '+1-555-555-5555',
        'priceRange' => '$$',
        'address' => [
            'streetAddress' => '123 Main St',
            'addressLocality' => 'Anytown',
            'postalCode' => '12345',
            'addressCountry' => 'US',
        ],
        'geo' => [
            'latitude' => '40.7128',
            'longitude' => '-74.0060',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Fallbacks
    |--------------------------------------------------------------------------
    |
    | Fallback configurations.
    |
    */
    'fallbacks' => [
        'separator' => '-',
        'site_name' => env('APP_NAME', 'Ghorbari'),
        'default_title' => '{title} {sep} {site_name}',
        'default_description' => 'Read more about {title} on {site_name}.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Site Verifications
    |--------------------------------------------------------------------------
    */
    'verifications' => [
        'google'    => env('SEO_GOOGLE_VERIFICATION', ''),
        'bing'      => env('SEO_BING_VERIFICATION', ''),
        'yandex'    => '',
        'pinterest' => '',
        'baidu'     => '',
    ],

    /*
    |--------------------------------------------------------------------------
    | Organization
    |--------------------------------------------------------------------------
    */
    'organization' => [
        'name'            => env('APP_NAME', 'Ghorbari'),
        'url'             => env('APP_URL', 'http://localhost'),
        'logo'            => '',
        'social_profiles' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Scripts
    |--------------------------------------------------------------------------
    */
    'scripts' => [
        'head'       => '',
        'body_start' => '',
        'footer'     => '',
    ],

    /*
    |--------------------------------------------------------------------------
    | Breadcrumbs
    |--------------------------------------------------------------------------
    */
    'breadcrumbs' => [
        'enabled'         => true,
        'separator'       => '»',
        'home_label'      => 'Home',
        'generate_schema' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Links
    |--------------------------------------------------------------------------
    */
    'links' => [
        'enabled' => true,
        'external_nofollow' => true,
        'external_new_tab' => true,
    ],
];
