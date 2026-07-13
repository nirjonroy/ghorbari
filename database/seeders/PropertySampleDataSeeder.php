<?php

namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PropertySampleDataSeeder extends Seeder
{
    public function run(): void
    {
        $amenities = collect([
            ['name' => 'Lift', 'icon' => 'bi-arrow-up-square'],
            ['name' => 'Generator Backup', 'icon' => 'bi-lightning-charge'],
            ['name' => 'Parking', 'icon' => 'bi-car-front'],
            ['name' => 'Security Guard', 'icon' => 'bi-shield-check'],
            ['name' => 'CCTV Surveillance', 'icon' => 'bi-camera-video'],
            ['name' => 'Gas Connection', 'icon' => 'bi-fire'],
            ['name' => 'Balcony', 'icon' => 'bi-door-open'],
            ['name' => 'Swimming Pool', 'icon' => 'bi-water'],
            ['name' => 'Gymnasium', 'icon' => 'bi-heart-pulse'],
            ['name' => 'Rooftop Garden', 'icon' => 'bi-flower1'],
            ['name' => 'Servant Room', 'icon' => 'bi-person-workspace'],
            ['name' => 'Community Hall', 'icon' => 'bi-building'],
        ])->mapWithKeys(function (array $data) {
            $amenity = Amenity::query()->updateOrCreate(
                ['slug' => Str::slug($data['name'])],
                [
                    'name' => $data['name'],
                    'icon' => $data['icon'],
                    'status' => 'active',
                ]
            );

            return [$data['name'] => $amenity];
        });

        $owner = User::query()->updateOrCreate(
            ['email' => 'sample.owner@example.com'],
            [
                'name' => 'Sample Property Owner',
                'account_type' => 'seller',
                'phone' => '+8801722000000',
                'district' => 'Dhaka',
                'division' => 'Dhaka',
                'country' => 'Bangladesh',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]
        );

        $types = collect(['Apartment', 'Duplex', 'House', 'Commercial', 'Land'])->mapWithKeys(function (string $name) {
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
                'title' => 'Dhanmondi Lake View Apartment',
                'listing_type' => 'rent',
                'price' => 85000,
                'rent_period' => 'monthly',
                'area_size' => 1650,
                'bedrooms' => 3,
                'bathrooms' => 3,
                'balconies' => 2,
                'description' => 'Road 7A, Dhanmondi, Dhaka',
                'is_featured' => true,
                'is_early_access' => false,
                'images' => ['property_img_1.jpg', 'property_img_2.jpg', 'property_img_3.jpg'],
                'amenities' => ['Lift', 'Generator Backup', 'Parking', 'Security Guard', 'Gas Connection'],
            ],
            [
                'type' => 'Duplex',
                'title' => 'Gulshan Diplomatic Zone Duplex',
                'listing_type' => 'sell',
                'price' => 145000000,
                'rent_period' => null,
                'area_size' => 4200,
                'bedrooms' => 5,
                'bathrooms' => 5,
                'balconies' => 3,
                'description' => 'Gulshan 2, Dhaka',
                'is_featured' => true,
                'is_early_access' => true,
                'images' => ['card_img_21.jpg', 'card_img_22.jpg', 'card_img_23.jpg'],
                'amenities' => ['Parking', 'Security Guard', 'CCTV Surveillance', 'Rooftop Garden', 'Servant Room'],
            ],
            [
                'type' => 'Commercial',
                'title' => 'Motijheel Commercial Office Space',
                'listing_type' => 'rent',
                'price' => 220000,
                'rent_period' => 'monthly',
                'area_size' => 2800,
                'bedrooms' => null,
                'bathrooms' => 4,
                'balconies' => null,
                'description' => 'Commercial area, Motijheel, Dhaka',
                'is_featured' => false,
                'is_early_access' => false,
                'images' => ['single_property_1.jpg', 'property_img_2.jpg', 'property_img_3.jpg'],
                'amenities' => ['Lift', 'Generator Backup', 'Parking', 'CCTV Surveillance'],
            ],
            [
                'type' => 'House',
                'title' => 'Rajshahi Family House',
                'listing_type' => 'sell',
                'price' => 18500000,
                'rent_period' => null,
                'area_size' => 2100,
                'land_size' => 4.5,
                'bedrooms' => 4,
                'bathrooms' => 3,
                'balconies' => 2,
                'description' => 'Padma Residential Area, Rajshahi',
                'is_featured' => false,
                'is_early_access' => false,
                'images' => ['card_img_21.jpg', 'card_img_22.jpg', 'card_img_23.jpg'],
                'amenities' => ['Parking', 'Gas Connection', 'Balcony', 'Rooftop Garden'],
            ],
            [
                'type' => 'Land',
                'title' => 'Purbachal Residential Plot',
                'listing_type' => 'sell',
                'price' => 32000000,
                'rent_period' => null,
                'area_size' => null,
                'land_size' => 5,
                'bedrooms' => null,
                'bathrooms' => null,
                'balconies' => null,
                'description' => 'Sector 10, Purbachal New Town, Dhaka',
                'is_featured' => true,
                'is_early_access' => false,
                'images' => ['single_property_1.jpg', 'property_img_1.jpg', 'property_img_2.jpg'],
                'amenities' => ['Security Guard'],
            ],
            [
                'type' => 'Apartment',
                'title' => 'Uttara Ready Flat',
                'listing_type' => 'sell',
                'price' => 12500000,
                'rent_period' => null,
                'area_size' => 1380,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'balconies' => 2,
                'description' => 'Sector 11, Uttara, Dhaka',
                'is_featured' => false,
                'is_early_access' => true,
                'images' => ['card_img_23.jpg', 'card_img_22.jpg', 'card_img_21.jpg'],
                'amenities' => ['Lift', 'Generator Backup', 'Parking', 'Balcony'],
            ],
        ];

        foreach ($properties as $index => $data) {
            $property = Property::query()->updateOrCreate(
                ['slug' => Str::slug($data['title'])],
                [
                    'owner_user_id' => $owner->id,
                    'property_type_id' => $types[$data['type']]->id,
                    'address_id' => 100 + $index,
                    'title' => $data['title'],
                    'listing_type' => $data['listing_type'],
                    'property_status' => 'available',
                    'price' => $data['price'],
                    'rent_period' => $data['rent_period'],
                    'area_size' => $data['area_size'] ?? null,
                    'land_size' => $data['land_size'] ?? null,
                    'bedrooms' => $data['bedrooms'],
                    'bathrooms' => $data['bathrooms'],
                    'balconies' => $data['balconies'],
                    'floor_no' => null,
                    'total_floors' => null,
                    'parking_spaces' => in_array('Parking', $data['amenities'], true) ? 1 : 0,
                    'furnishing_status' => $data['type'] === 'Land' ? null : 'ready',
                    'description' => $data['description'],
                    'verification_status' => 'approved',
                    'is_featured' => $data['is_featured'],
                    'is_early_access' => $data['is_early_access'],
                    'is_published' => true,
                    'published_at' => now()->subDays($index + 5),
                ]
            );

            $property->amenities()->sync(
                collect($data['amenities'])
                    ->map(fn (string $name) => $amenities[$name]->id)
                    ->all()
            );

            $property->media()->delete();

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

        Property::query()
            ->whereIn('slug', [
                'banani-modern-residence',
                'bashundhara-furnished-flat',
                'chattogram-luxury-duplex',
                'sylhet-premium-family-home',
            ])
            ->get()
            ->each(function (Property $property) use ($amenities) {
                $property->amenities()->syncWithoutDetaching([
                    $amenities['Lift']->id,
                    $amenities['Generator Backup']->id,
                    $amenities['Parking']->id,
                    $amenities['Security Guard']->id,
                ]);
            });
    }
}
