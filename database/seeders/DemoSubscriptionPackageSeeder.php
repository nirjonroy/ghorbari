<?php

namespace Database\Seeders;

use App\Models\SubscriptionPackage;
use Illuminate\Database\Seeder;

class DemoSubscriptionPackageSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->packages() as $package) {
            SubscriptionPackage::updateOrCreate(
                ['slug' => $package['slug']],
                $package
            );
        }
    }

    private function packages(): array
    {
        return [
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'description' => 'For owners testing their first property listing.',
                'price' => 499,
                'currency' => 'BDT',
                'duration_days' => 30,
                'property_limit' => 2,
                'featured_property_limit' => 0,
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Growth',
                'slug' => 'growth',
                'description' => 'For active owners and small agents who need more listing capacity.',
                'price' => 1499,
                'currency' => 'BDT',
                'duration_days' => 30,
                'property_limit' => 10,
                'featured_property_limit' => 3,
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Agency Pro',
                'slug' => 'agency-pro',
                'description' => 'For agencies managing a larger property portfolio.',
                'price' => 4999,
                'currency' => 'BDT',
                'duration_days' => 30,
                'property_limit' => null,
                'featured_property_limit' => 15,
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];
    }
}
