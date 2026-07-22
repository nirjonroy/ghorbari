<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoBlogSeeder extends Seeder
{
    public function run(): void
    {
        collect([
            [
                'name' => 'Market Update',
                'slug' => 'market-update',
                'description' => 'Bangladesh property market news and pricing updates.',
                'display_order' => 1,
            ],
            [
                'name' => 'Buying Guide',
                'slug' => 'buying-guide',
                'description' => 'Practical guides for property buyers.',
                'display_order' => 2,
            ],
            [
                'name' => 'Rental Guide',
                'slug' => 'rental-guide',
                'description' => 'Rental tips for tenants and landlords.',
                'display_order' => 3,
            ],
        ])->each(fn (array $category) => BlogCategory::updateOrCreate(
            ['slug' => $category['slug']],
            $category + ['is_active' => true]
        ));

        collect($this->posts())->each(function (array $post, int $index): void {
            $category = BlogCategory::where('slug', $post['category'])->first();

            BlogPost::updateOrCreate(
                ['slug' => Str::slug($post['title'])],
                [
                    'blog_category_id' => $category?->id,
                    'title' => $post['title'],
                    'author_name' => $post['author_name'],
                    'excerpt' => $post['excerpt'],
                    'content' => $this->content($post['excerpt']),
                    'quote' => 'A practical property decision starts with clean documents, reliable location data, and realistic budget planning.',
                    'featured_image_path' => $post['image'],
                    'featured_image_source' => 'Land Site demo content',
                    'meta_description' => $post['excerpt'],
                    'tags' => $post['tags'],
                    'comments' => [],
                    'published_at' => now()->subDays($index),
                    'is_published' => true,
                    'show_on_home' => $index < 3,
                ]
            );
        });
    }

    private function posts(): array
    {
        return [
            [
                'category' => 'market-update',
                'title' => 'Dhaka Apartment Prices: What Buyers Should Watch This Year',
                'author_name' => 'Land Site Editorial',
                'excerpt' => "Understand demand, location premiums, and practical signals before you make an offer in Bangladesh's busiest housing market.",
                'image' => 'frontend/assets/images/post_img_1.jpg',
                'tags' => ['Dhaka', 'Apartment', 'Market'],
            ],
            [
                'category' => 'buying-guide',
                'title' => 'Documents To Verify Before Buying Property In Bangladesh',
                'author_name' => 'BlackTech Property Desk',
                'excerpt' => 'A simple checklist for ownership, mutation, utility connections, and handover readiness before buying a property.',
                'image' => 'frontend/assets/images/post_img_3.jpg',
                'tags' => ['Documents', 'Buying', 'Legal'],
            ],
            [
                'category' => 'rental-guide',
                'title' => 'Apartment Rental Checklist For Bangladesh Tenants',
                'author_name' => 'Land Site Editorial',
                'excerpt' => 'Review utility bills, lift backup, parking, security, agreement terms, and handover condition before renting.',
                'image' => 'frontend/assets/images/post_img_2.jpg',
                'tags' => ['Rent', 'Checklist', 'Tenant'],
            ],
            [
                'category' => 'buying-guide',
                'title' => 'How To Plan Your Home Loan Budget In BDT',
                'author_name' => 'Finance Team',
                'excerpt' => 'Compare down payment, monthly installments, registration costs, and safety margins before shortlisting homes.',
                'image' => 'frontend/assets/images/post_img_7.jpg',
                'tags' => ['Finance', 'Loan', 'Budget'],
            ],
            [
                'category' => 'market-update',
                'title' => 'Best Areas In Dhaka For First Time Home Buyers',
                'author_name' => 'Land Site Research',
                'excerpt' => 'Compare commute, schools, hospitals, resale value, and neighborhood growth before choosing your first home.',
                'image' => 'frontend/assets/images/post_img_8.jpg',
                'tags' => ['Dhaka', 'Neighborhood', 'First Home'],
            ],
        ];
    }

    private function content(string $excerpt): string
    {
        return <<<HTML
<h2>Overview</h2>
<p>{$excerpt}</p>
<h2>What To Check</h2>
<p>Compare location, documents, price, building quality, maintenance, and future resale value before making a final decision.</p>
<ul>
    <li>Review ownership and approval documents.</li>
    <li>Compare nearby property prices.</li>
    <li>Inspect road access, utilities, parking, and security.</li>
</ul>
<h2>Final Advice</h2>
<p>Use verified data and local guidance before committing to a property decision.</p>
HTML;
    }
}
