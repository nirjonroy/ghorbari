<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ApiTesterController extends Controller
{
    public function index(): View
    {
        return view('Admin.api_tester.index', [
            'resources' => [
                'site-info',
                'abouts',
                'sliders',
                'users',
                'property-types',
                'amenities',
                'properties',
                'divisions',
                'districts',
                'areas',
                'blog-categories',
                'blog-posts',
                'blog-comments',
                'blog-pages',
                'contact-messages',
                'agencies',
                'agent-profiles',
                'roles',
                'permissions',
                'admins',
            ],
            'examples' => [
                'Frontend Home' => [
                    'method' => 'GET',
                    'path' => '/api/home',
                    'body' => null,
                ],
                'Frontend Property Search' => [
                    'method' => 'GET',
                    'path' => '/api/property/for-sale?q=Dhaka',
                    'body' => null,
                ],
                'Frontend Property Details' => [
                    'method' => 'GET',
                    'path' => '/api/property-details/banani-modern-residence-road-12-banani-dhaka-1',
                    'body' => null,
                ],
                'Login' => [
                    'method' => 'POST',
                    'path' => '/api/admin/login',
                    'body' => [
                        'email' => 'admin@example.com',
                        'password' => 'password',
                        'device_name' => 'browser-api-tester',
                    ],
                ],
                'Dashboard' => [
                    'method' => 'GET',
                    'path' => '/api/admin/dashboard',
                    'body' => null,
                ],
                'List Properties' => [
                    'method' => 'GET',
                    'path' => '/api/admin/properties',
                    'body' => null,
                ],
                'Create Division' => [
                    'method' => 'POST',
                    'path' => '/api/admin/divisions',
                    'body' => [
                        'name' => 'Dhaka',
                        'status' => true,
                    ],
                ],
                'Update Blog Comment Status' => [
                    'method' => 'PATCH',
                    'path' => '/api/admin/blog-comments/1',
                    'body' => [
                        'blog_post_id' => 1,
                        'name' => 'Visitor',
                        'comment' => 'Comment text',
                        'is_approved' => true,
                    ],
                ],
            ],
            'frontendApis' => [
                'Main Pages' => [
                    ['label' => 'Home', 'method' => 'GET', 'path' => '/api/home'],
                    ['label' => 'Buy Hub', 'method' => 'GET', 'path' => '/api/for-sale'],
                    ['label' => 'Rent Hub', 'method' => 'GET', 'path' => '/api/for-rent'],
                    ['label' => 'Sell Hub', 'method' => 'GET', 'path' => '/api/sell'],
                    ['label' => 'Open Houses', 'method' => 'GET', 'path' => '/api/open-houses'],
                    ['label' => 'Early Access', 'method' => 'GET', 'path' => '/api/early-access'],
                    ['label' => 'Calculator', 'method' => 'GET', 'path' => '/api/calculator'],
                    ['label' => 'Agents', 'method' => 'GET', 'path' => '/api/real-estate-agents'],
                    ['label' => 'About', 'method' => 'GET', 'path' => '/api/about'],
                    ['label' => 'Contact', 'method' => 'GET', 'path' => '/api/contact'],
                ],
                'Property Search' => [
                    ['label' => 'Buy Search', 'method' => 'GET', 'path' => '/api/property/for-sale?q=Dhaka'],
                    ['label' => 'District Page', 'method' => 'GET', 'path' => '/api/property/dhaka'],
                    ['label' => 'City Page', 'method' => 'GET', 'path' => '/api/property/dhaka/gulshan'],
                    ['label' => 'Local Area Page', 'method' => 'GET', 'path' => '/api/property/dhaka/gulshan/gulshan-1'],
                    ['label' => 'By Category', 'method' => 'GET', 'path' => '/api/property/for-rent/residential'],
                    ['label' => 'By Type', 'method' => 'GET', 'path' => '/api/property/for-rent/residential/apartment'],
                    ['label' => 'By District', 'method' => 'GET', 'path' => '/api/property/for-rent/apartment/dhaka'],
                    ['label' => 'By City', 'method' => 'GET', 'path' => '/api/property/for-rent/apartment/dhaka/gulshan'],
                    ['label' => 'By Local Area', 'method' => 'GET', 'path' => '/api/property/for-rent/apartment/dhaka/gulshan/gulshan-1'],
                    ['label' => 'Land Sale City', 'method' => 'GET', 'path' => '/api/property/for-sale/residential/land-plot/gulshan'],
                    ['label' => 'Property Details', 'method' => 'GET', 'path' => '/api/property-details/banani-modern-residence-road-12-banani-dhaka-1'],
                ],
                'Content Pages' => [
                    ['label' => 'Blog List', 'method' => 'GET', 'path' => '/api/blog'],
                    ['label' => 'Blog Detail', 'method' => 'GET', 'path' => '/api/blog/sample-real-estate-guide'],
                    ['label' => 'Custom Page', 'method' => 'GET', 'path' => '/api/custom-pages/guides/buy-guide'],
                ],
            ],
        ]);
    }
}
