<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\PropertyType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EarlyAccessPropertySeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::query()->updateOrCreate(
            ['email' => 'early.owner@example.com'],
            [
                'name' => 'Early Access Owner',
                'account_type' => 'seller',
                'phone' => '+8801711000000',
                'district' => 'Dhaka',
                'division' => 'Dhaka',
                'country' => 'Bangladesh',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]
        );

        $types = collect(['Apartment', 'Duplex', 'House'])->mapWithKeys(function (string $name) {
            $type = PropertyType::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'icon' => null,
                    'status' => 'active',
                ]
            );

            return [$name => $type];
        });

        $properties = [
            [
                'type' => 'Apartment',
                'title' => 'Banani Modern Residence',
                'listing_type' => 'sell',
                'price' => 73500000,
                'area_size' => 2120,
                'bedrooms' => 4,
                'bathrooms' => 3,
                'balconies' => 2,
                'description' => 'Road 12, Banani, Dhaka',
                'images' => ['card_img_1.jpg', 'card_img_2.jpg', 'card_img_3.jpg'],
            ],
            [
                'type' => 'Apartment',
                'title' => 'Bashundhara Furnished Flat',
                'listing_type' => 'sell',
                'price' => 48900000,
                'area_size' => 1540,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'balconies' => 1,
                'description' => 'Avenue 5, Bashundhara R/A, Dhaka',
                'images' => ['card_img_4.jpg', 'card_img_5.jpg', 'card_img_6.jpg'],
            ],
            [
                'type' => 'Duplex',
                'title' => 'Chattogram Luxury Duplex',
                'listing_type' => 'sell',
                'price' => 92000000,
                'area_size' => 3010,
                'bedrooms' => 5,
                'bathrooms' => 4,
                'balconies' => 3,
                'description' => 'Nasirabad Housing Society, Chattogram',
                'images' => ['card_img_7.jpg', 'card_img_8.jpg', 'card_img_9.jpg'],
            ],
            [
                'type' => 'House',
                'title' => 'Sylhet Premium Family Home',
                'listing_type' => 'sell',
                'price' => 36500000,
                'area_size' => 2450,
                'bedrooms' => 4,
                'bathrooms' => 3,
                'balconies' => 2,
                'description' => 'Shahjalal Uposhohor, Sylhet',
                'images' => ['card_img_10.jpg', 'card_img_11.jpg', 'card_img_12.jpg'],
            ],
        ];

        foreach ($properties as $index => $data) {
            $property = Property::query()->updateOrCreate(
                ['slug' => Str::slug($data['title'])],
                [
                    'owner_user_id' => $owner->id,
                    'property_type_id' => $types[$data['type']]->id,
                    'address_id' => $index + 1,
                    'title' => $data['title'],
                    'listing_type' => $data['listing_type'],
                    'property_status' => 'available',
                    'price' => $data['price'],
                    'rent_period' => null,
                    'area_size' => $data['area_size'],
                    'land_size' => null,
                    'bedrooms' => $data['bedrooms'],
                    'bathrooms' => $data['bathrooms'],
                    'balconies' => $data['balconies'],
                    'floor_no' => null,
                    'total_floors' => null,
                    'parking_spaces' => 1,
                    'furnishing_status' => 'ready',
                    'description' => $data['description'],
                    'verification_status' => 'approved',
                    'is_featured' => $index < 2,
                    'is_early_access' => true,
                    'is_published' => true,
                    'published_at' => now()->subDays($index),
                ]
            );

            foreach ($data['images'] as $sortOrder => $image) {
                $property->media()->updateOrCreate(
                    ['file_path' => 'frontend/assets/images/'.$image],
                    [
                        'media_type' => 'image',
                        'space_name' => ['Exterior', 'Interior', 'Bedroom'][$sortOrder] ?? null,
                        'alt_text' => $data['title'].' photo '.($sortOrder + 1),
                        'is_primary' => $sortOrder === 0,
                        'sort_order' => $sortOrder,
                    ]
                );
            }
        }
    }
}
