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
                'roles',
                'permissions',
                'admins',
            ],
            'examples' => [
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
        ]);
    }
}
