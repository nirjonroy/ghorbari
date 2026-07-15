<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\City;
use App\Models\District;
use App\Models\Property;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PropertyLocationBackfillSeeder extends Seeder
{
    public function run(): void
    {
        $assignments = [
            'dhanmondi-lake-view-apartment' => ['district' => 'Dhaka', 'city' => 'Dhanmondi', 'area' => 'Road 7A', 'category' => 'residential'],
            'gulshan-diplomatic-zone-duplex' => ['district' => 'Dhaka', 'city' => 'Gulshan', 'area' => 'Gulshan 2', 'category' => 'residential', 'open_house' => true],
            'motijheel-commercial-office-space' => ['district' => 'Dhaka', 'city' => 'Motijheel', 'area' => 'Commercial Area', 'category' => 'commercial'],
            'rajshahi-family-house' => ['district' => 'Rajshahi', 'city' => 'Rajshahi', 'area' => 'Padma Residential Area', 'category' => 'residential'],
            'purbachal-residential-plot' => ['district' => 'Dhaka', 'city' => 'Purbachal', 'area' => 'Sector 10', 'category' => 'land', 'open_house' => true],
            'uttara-ready-flat' => ['district' => 'Dhaka', 'city' => 'Uttara', 'area' => 'Sector 11', 'category' => 'residential', 'open_house' => true],
            'banani-modern-residence' => ['district' => 'Dhaka', 'city' => 'Banani', 'area' => 'Road 12', 'category' => 'residential', 'open_house' => true],
            'bashundhara-furnished-flat' => ['district' => 'Dhaka', 'city' => 'Bashundhara', 'area' => 'Bashundhara R/A', 'category' => 'residential'],
            'chattogram-luxury-duplex' => ['district' => 'Chattogram', 'city' => 'Chattogram', 'area' => 'Nasirabad Housing Society', 'category' => 'residential'],
            'sylhet-premium-family-home' => ['district' => 'Sylhet', 'city' => 'Sylhet', 'area' => 'Shahjalal Uposhohor', 'category' => 'residential'],
        ];

        foreach ($assignments as $propertySlug => $location) {
            $district = District::query()->where('slug', Str::slug($location['district']))->first();

            if (! $district) {
                continue;
            }

            $city = City::query()->updateOrCreate(
                ['district_id' => $district->id, 'name' => $location['city']],
                ['slug' => Str::slug($location['city']), 'status' => true]
            );

            $area = Area::query()->updateOrCreate(
                ['district_id' => $district->id, 'name' => $location['area']],
                [
                    'city_id' => $city->id,
                    'slug' => Str::slug($location['area']),
                    'post_office' => $location['city'],
                    'postal_code' => null,
                    'status' => true,
                ]
            );

            Property::query()
                ->where('slug', $propertySlug)
                ->update([
                    'district_id' => $district->id,
                    'city_id' => $city->id,
                    'area_id' => $area->id,
                    'property_category' => $location['category'],
                    'is_open_house' => $location['open_house'] ?? false,
                ]);
        }
    }
}
