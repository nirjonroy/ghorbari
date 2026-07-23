<?php

namespace Database\Seeders;

use App\Models\CustomPage;
use Illuminate\Database\Seeder;

class DemoCustomPageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'page_name' => 'Buy Guide',
                'slug' => 'buy-guide',
                'url_path' => 'guides/buy-guide',
                'subtitle' => 'Practical steps for buying property in Bangladesh',
                'short_description' => 'Learn how to compare locations, verify documents, and plan your property purchase.',
                'content' => '<h2>How to buy property with confidence</h2><p>Start with a clear budget, shortlist verified areas, compare recent prices, and review ownership documents before making an offer.</p><p>Use Land Site to compare listings, save homes, and connect with agents who understand your preferred district, city, and local area.</p>',
                'seo_title' => 'Buy Guide | Land Site',
                'seo_description' => 'A practical guide for buying homes, apartments, and land in Bangladesh.',
                'meta_title' => 'Buy Guide | Land Site',
                'meta_description' => 'A practical guide for buying homes, apartments, and land in Bangladesh.',
                'author' => 'BlackTech Corp.',
                'publisher' => 'Land Site',
                'copyright' => 'BlackTech Corp.',
                'site_name' => 'Land Site',
                'keywords' => 'buy property Bangladesh, home buying guide, land site',
            ],
            [
                'page_name' => 'Why Choose Us',
                'slug' => 'why-choose-us',
                'url_path' => 'why-choose-us',
                'subtitle' => 'Verified property discovery for buyers, sellers, and renters',
                'short_description' => 'See how Land Site helps people search, compare, and manage property decisions.',
                'content' => '<h2>Built for local property decisions</h2><p>Land Site brings property listings, location filters, agent connections, and user dashboards into one workspace.</p><p>Our tools are designed for Bangladesh real estate workflows, including district, city, area, property type, and listing purpose searches.</p>',
                'seo_title' => 'Why Choose Land Site',
                'seo_description' => 'Why buyers, renters, sellers, and property owners use Land Site.',
                'meta_title' => 'Why Choose Land Site',
                'meta_description' => 'Why buyers, renters, sellers, and property owners use Land Site.',
                'author' => 'BlackTech Corp.',
                'publisher' => 'Land Site',
                'copyright' => 'BlackTech Corp.',
                'site_name' => 'Land Site',
                'keywords' => 'Land Site, real estate Bangladesh, property platform',
            ],
            [
                'page_name' => 'Dhaka Property Sitemap',
                'slug' => 'dhaka-property-sitemap',
                'url_path' => 'sitemap/dhaka/property',
                'subtitle' => 'Browse property pages for Dhaka',
                'short_description' => 'A simple test page for city-based sitemap URL structures.',
                'content' => '<h2>Dhaka property sitemap</h2><p>This demo page can be used to test dynamic custom URLs such as sitemap pages, guide pages, or any future content page.</p><ul><li>Dhaka homes for sale</li><li>Dhaka apartments for rent</li><li>Dhaka land listings</li></ul>',
                'seo_title' => 'Dhaka Property Sitemap',
                'seo_description' => 'Browse Dhaka property sitemap links and custom page URL examples.',
                'meta_title' => 'Dhaka Property Sitemap',
                'meta_description' => 'Browse Dhaka property sitemap links and custom page URL examples.',
                'author' => 'BlackTech Corp.',
                'publisher' => 'Land Site',
                'copyright' => 'BlackTech Corp.',
                'site_name' => 'Land Site',
                'keywords' => 'Dhaka property, Dhaka sitemap, Bangladesh real estate',
            ],
        ];

        foreach ($pages as $page) {
            CustomPage::query()->updateOrCreate(
                ['url_path' => $page['url_path']],
                $page + [
                    'template_type' => 'default',
                    'status' => 'published',
                    'published_at' => now(),
                ]
            );
        }
    }
}
