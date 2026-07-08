<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\District;
use App\Models\Division;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BangladeshLocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            'Dhaka' => [
                'Dhaka' => [
                    ['name' => 'Dhanmondi', 'post_office' => 'Dhanmondi', 'postal_code' => '1209'],
                    ['name' => 'Mirpur', 'post_office' => 'Mirpur', 'postal_code' => '1216'],
                    ['name' => 'Uttara', 'post_office' => 'Uttara Model Town', 'postal_code' => '1230'],
                ],
                'Gazipur' => [
                    ['name' => 'Tongi', 'post_office' => 'Tongi', 'postal_code' => '1710'],
                    ['name' => 'Joydebpur', 'post_office' => 'Joydebpur', 'postal_code' => '1700'],
                ],
                'Narayanganj' => [
                    ['name' => 'Fatullah', 'post_office' => 'Fatullah', 'postal_code' => '1421'],
                    ['name' => 'Siddhirganj', 'post_office' => 'Siddhirganj', 'postal_code' => '1430'],
                ],
            ],
            'Chattogram' => [
                'Chattogram' => [
                    ['name' => 'Agrabad', 'post_office' => 'Agrabad', 'postal_code' => '4100'],
                    ['name' => 'Halishahar', 'post_office' => 'Halishahar', 'postal_code' => '4216'],
                    ['name' => 'Panchlaish', 'post_office' => 'Panchlaish', 'postal_code' => '4203'],
                ],
                'Cumilla' => [
                    ['name' => 'Kotbari', 'post_office' => 'Kotbari', 'postal_code' => '3503'],
                    ['name' => 'Kandirpar', 'post_office' => 'Cumilla Sadar', 'postal_code' => '3500'],
                ],
                'Coxs Bazar' => [
                    ['name' => 'Coxs Bazar Sadar', 'post_office' => 'Coxs Bazar', 'postal_code' => '4700'],
                    ['name' => 'Teknaf', 'post_office' => 'Teknaf', 'postal_code' => '4760'],
                ],
            ],
            'Rajshahi' => [
                'Rajshahi' => [
                    ['name' => 'Boalia', 'post_office' => 'Rajshahi Sadar', 'postal_code' => '6000'],
                    ['name' => 'Motihar', 'post_office' => 'Rajshahi University', 'postal_code' => '6205'],
                ],
                'Bogura' => [
                    ['name' => 'Bogura Sadar', 'post_office' => 'Bogura Sadar', 'postal_code' => '5800'],
                    ['name' => 'Sherpur', 'post_office' => 'Sherpur', 'postal_code' => '5840'],
                ],
            ],
            'Khulna' => [
                'Khulna' => [
                    ['name' => 'Sonadanga', 'post_office' => 'Sonadanga', 'postal_code' => '9100'],
                    ['name' => 'Khalishpur', 'post_office' => 'Khalishpur', 'postal_code' => '9000'],
                ],
                'Jashore' => [
                    ['name' => 'Jashore Sadar', 'post_office' => 'Jashore Sadar', 'postal_code' => '7400'],
                    ['name' => 'Chowgacha', 'post_office' => 'Chowgacha', 'postal_code' => '7410'],
                ],
            ],
            'Barishal' => [
                'Barishal' => [
                    ['name' => 'Barishal Sadar', 'post_office' => 'Barishal Sadar', 'postal_code' => '8200'],
                    ['name' => 'Nathullabad', 'post_office' => 'Nathullabad', 'postal_code' => '8200'],
                ],
                'Patuakhali' => [
                    ['name' => 'Patuakhali Sadar', 'post_office' => 'Patuakhali', 'postal_code' => '8600'],
                    ['name' => 'Kuakata', 'post_office' => 'Kuakata', 'postal_code' => '8652'],
                ],
            ],
            'Sylhet' => [
                'Sylhet' => [
                    ['name' => 'Zindabazar', 'post_office' => 'Sylhet Sadar', 'postal_code' => '3100'],
                    ['name' => 'Amberkhana', 'post_office' => 'Sylhet Sadar', 'postal_code' => '3100'],
                ],
                'Moulvibazar' => [
                    ['name' => 'Sreemangal', 'post_office' => 'Sreemangal', 'postal_code' => '3210'],
                    ['name' => 'Moulvibazar Sadar', 'post_office' => 'Moulvibazar', 'postal_code' => '3200'],
                ],
            ],
            'Rangpur' => [
                'Rangpur' => [
                    ['name' => 'Rangpur Sadar', 'post_office' => 'Rangpur Sadar', 'postal_code' => '5400'],
                    ['name' => 'Mithapukur', 'post_office' => 'Mithapukur', 'postal_code' => '5460'],
                ],
                'Dinajpur' => [
                    ['name' => 'Dinajpur Sadar', 'post_office' => 'Dinajpur Sadar', 'postal_code' => '5200'],
                    ['name' => 'Birampur', 'post_office' => 'Birampur', 'postal_code' => '5266'],
                ],
            ],
            'Mymensingh' => [
                'Mymensingh' => [
                    ['name' => 'Mymensingh Sadar', 'post_office' => 'Mymensingh Sadar', 'postal_code' => '2200'],
                    ['name' => 'Trishal', 'post_office' => 'Trishal', 'postal_code' => '2220'],
                ],
                'Jamalpur' => [
                    ['name' => 'Jamalpur Sadar', 'post_office' => 'Jamalpur Sadar', 'postal_code' => '2000'],
                    ['name' => 'Sarishabari', 'post_office' => 'Sarishabari', 'postal_code' => '2050'],
                ],
            ],
        ];

        foreach ($locations as $divisionName => $districts) {
            $division = Division::updateOrCreate(
                ['name' => $divisionName],
                ['slug' => Str::slug($divisionName), 'status' => true]
            );

            foreach ($districts as $districtName => $areas) {
                $district = District::updateOrCreate(
                    ['division_id' => $division->id, 'name' => $districtName],
                    ['slug' => Str::slug($districtName), 'status' => true]
                );

                foreach ($areas as $area) {
                    Area::updateOrCreate(
                        ['district_id' => $district->id, 'name' => $area['name']],
                        [
                            'slug' => Str::slug($area['name']),
                            'post_office' => $area['post_office'],
                            'postal_code' => $area['postal_code'],
                            'status' => true,
                        ]
                    );
                }
            }
        }
    }
}
