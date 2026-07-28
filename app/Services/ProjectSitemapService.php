<?php

namespace App\Services;

use App\Models\Area;
use App\Models\BlogPost;
use App\Models\City;
use App\Models\CustomPage;
use App\Models\District;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\SiteInfo;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Nirjon\LaravelSeo\Models\SeoGeneratedPage;
use Nirjon\LaravelSeo\Services\SitemapService;

class ProjectSitemapService extends SitemapService
{
    public function generateXml(?string $requestedFilename = null): string
    {
        ob_start();
        $this->streamXml($requestedFilename);

        return (string) ob_get_clean();
    }

    public function streamXml(?string $requestedFilename = null): void
    {
        $requestedFilename = $this->sanitizeFilename($requestedFilename ?: $this->baseFilename());
        $entries = $this->mainEntries();
        $urlsPerFile = $this->urlsPerFile();

        if ($entries->count() > $urlsPerFile && $this->isIndexFilename($requestedFilename)) {
            $this->streamIndex($entries->count());
            return;
        }

        $pageNumber = $entries->count() > $urlsPerFile
            ? $this->pageNumberForFilename($requestedFilename)
            : 1;

        if ($pageNumber === null) {
            return;
        }

        $this->streamUrlset($entries->forPage($pageNumber, $urlsPerFile));
    }

    public function canServe(?string $requestedFilename = null): bool
    {
        $requestedFilename = $this->sanitizeFilename($requestedFilename ?: $this->baseFilename());

        if ($this->isIndexFilename($requestedFilename)) {
            return true;
        }

        return $this->mainEntries()->count() > $this->urlsPerFile()
            && $this->pageNumberForFilename($requestedFilename) !== null;
    }

    public function totalUrlCount(): int
    {
        return $this->mainEntries()->count();
    }

    public function pageCount(): int
    {
        return (int) ceil(max(1, $this->totalUrlCount()) / $this->urlsPerFile());
    }

    public function pageFilenames(): array
    {
        if ($this->pageCount() <= 1) {
            return [$this->baseFilename()];
        }

        return array_map(fn ($page) => $this->childFilename($page), range(1, $this->pageCount()));
    }

    public function generatePageForgeXml(): string
    {
        return $this->xmlDocument($this->pageForgeEntries());
    }

    private function mainEntries(): Collection
    {
        return collect()
            ->merge($this->staticRouteEntries())
            ->merge($this->modelEntries())
            ->unique('loc')
            ->sortBy('loc')
            ->values();
    }

    private function staticRouteEntries(): Collection
    {
        return collect(Route::getRoutes())
            ->filter(function ($route) {
                $uri = trim($route->uri(), '/');
                $name = (string) $route->getName();

                if (! in_array('GET', $route->methods(), true)) {
                    return false;
                }

                if (str_contains($uri, '{')) {
                    return false;
                }

                if ($uri === '' || str_starts_with($name, 'frontend.')) {
                    return true;
                }

                return false;
            })
            ->reject(fn ($route) => $this->isExcludedUri($route->uri()) || $this->isExcludedName((string) $route->getName()))
            ->map(fn ($route) => [
                'loc' => $this->absoluteUrl('/'.trim($route->uri(), '/')),
                'lastmod' => now(),
                'changefreq' => $route->uri() === '/' ? 'daily' : 'weekly',
                'priority' => $route->uri() === '/' ? '1.0' : '0.8',
            ]);
    }

    private function modelEntries(): Collection
    {
        return collect()
            ->merge($this->propertyEntries())
            ->merge($this->blogEntries())
            ->merge($this->customPageEntries())
            ->merge($this->locationEntries())
            ->merge($this->propertyTypeEntries());
    }

    private function propertyEntries(): Collection
    {
        if (! $this->hasTable(Property::class)) {
            return collect();
        }

        return Property::query()
            ->with(['area:id,slug', 'city:id,slug', 'district:id,slug'])
            ->where('is_published', true)
            ->when(Schema::hasColumn('properties', 'robots'), fn (Builder $query) => $query->where(function ($inner) {
                $inner->whereNull('robots')->orWhere('robots', 'not like', '%noindex%');
            }))
            ->get()
            ->map(fn (Property $property) => [
                'loc' => $this->absoluteUrl($property->detailUrl()),
                'lastmod' => $property->updated_at,
                'changefreq' => 'weekly',
                'priority' => $property->is_featured ? '0.9' : '0.8',
            ]);
    }

    private function blogEntries(): Collection
    {
        if (! $this->hasTable(BlogPost::class)) {
            return collect();
        }

        return BlogPost::query()
            ->where('is_published', true)
            ->when(Schema::hasColumn('blog_posts', 'robots'), fn (Builder $query) => $query->where(function ($inner) {
                $inner->whereNull('robots')->orWhere('robots', 'not like', '%noindex%');
            }))
            ->get()
            ->map(fn (BlogPost $post) => [
                'loc' => $this->absoluteUrl(route('frontend.blog.show', $post->slug)),
                'lastmod' => $post->updated_at,
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ]);
    }

    private function customPageEntries(): Collection
    {
        if (! $this->hasTable(CustomPage::class)) {
            return collect();
        }

        return CustomPage::query()
            ->published()
            ->when(Schema::hasColumn('custom_pages', 'robots'), fn (Builder $query) => $query->where(function ($inner) {
                $inner->whereNull('robots')->orWhere('robots', 'not like', '%noindex%');
            }))
            ->get()
            ->map(fn (CustomPage $page) => [
                'loc' => $this->absoluteUrl($page->public_url),
                'lastmod' => $page->updated_at,
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ]);
    }

    private function locationEntries(): Collection
    {
        $entries = collect();

        if ($this->hasTable(District::class)) {
            District::query()
                ->where('status', true)
                ->get()
                ->each(fn (District $district) => $entries->push([
                    'loc' => $this->absoluteUrl(route('frontend.property.district', $district->slug)),
                    'lastmod' => $district->updated_at,
                    'changefreq' => 'weekly',
                    'priority' => '0.7',
                ]));
        }

        if ($this->hasTable(City::class)) {
            City::query()
                ->with('district:id,slug')
                ->where('status', true)
                ->get()
                ->each(function (City $city) use ($entries) {
                    if (! $city->district?->slug) {
                        return;
                    }

                    $entries->push([
                        'loc' => $this->absoluteUrl(route('frontend.property.city', [$city->district->slug, $city->slug])),
                        'lastmod' => $city->updated_at,
                        'changefreq' => 'weekly',
                        'priority' => '0.7',
                    ]);
                });
        }

        if ($this->hasTable(Area::class)) {
            Area::query()
                ->with(['district:id,slug', 'city:id,slug'])
                ->where('status', true)
                ->get()
                ->each(function (Area $area) use ($entries) {
                    if (! $area->district?->slug || ! $area->city?->slug) {
                        return;
                    }

                    $entries->push([
                        'loc' => $this->absoluteUrl(route('frontend.property.local-area', [$area->district->slug, $area->city->slug, $area->slug])),
                        'lastmod' => $area->updated_at,
                        'changefreq' => 'weekly',
                        'priority' => '0.7',
                    ]);
                });
        }

        return $entries;
    }

    private function propertyTypeEntries(): Collection
    {
        if (! $this->hasTable(PropertyType::class)) {
            return collect();
        }

        return PropertyType::query()
            ->where('status', 'active')
            ->get()
            ->flatMap(function (PropertyType $type) {
                return collect(['for-sale', 'for-rent', 'sell'])
                    ->map(fn ($purpose) => [
                        'loc' => $this->absoluteUrl(route('frontend.property.type', [$purpose, $this->categoryForType($type), $type->slug])),
                        'lastmod' => $type->updated_at,
                        'changefreq' => 'weekly',
                        'priority' => '0.7',
                    ]);
            });
    }

    private function pageForgeEntries(): Collection
    {
        try {
            if (! Schema::hasTable('nirjon_seo_generated_pages')) {
                return collect();
            }

            return SeoGeneratedPage::query()
                ->select(['url_slug', 'updated_at'])
                ->orderBy('url_slug')
                ->get()
                ->filter(fn ($page) => is_string($page->url_slug) && trim($page->url_slug) !== '')
                ->map(fn ($page) => [
                    'loc' => $this->absoluteUrl('/'.trim($page->url_slug, '/')),
                    'lastmod' => $page->updated_at,
                    'changefreq' => 'weekly',
                    'priority' => '0.7',
                ])
                ->unique('loc')
                ->values();
        } catch (\Throwable $exception) {
            return collect();
        }
    }

    private function hasTable(string $model): bool
    {
        try {
            return Schema::hasTable((new $model())->getTable());
        } catch (\Throwable $exception) {
            return false;
        }
    }

    private function categoryForType(PropertyType $type): string
    {
        $slug = Str::lower($type->slug);

        if (str_contains($slug, 'land') || str_contains($slug, 'plot')) {
            return 'land';
        }

        if (str_contains($slug, 'office') || str_contains($slug, 'shop') || str_contains($slug, 'commercial')) {
            return 'commercial';
        }

        return 'residential';
    }

    private function isExcludedUri(string $uri): bool
    {
        return Str::is([
            'api*',
            'admin*',
            'dashboard*',
            'user*',
            'login',
            'register',
            'forgot-password',
            'reset-password*',
            'verify-email*',
            'confirm-password',
            'email/verification-notification',
            'profile*',
            'favorites*',
            'support-chats*',
            'sitemap*',
            'pageforge*',
            'robots.txt',
            '_ignition*',
            'sanctum*',
        ], trim($uri, '/'));
    }

    private function isExcludedName(string $name): bool
    {
        return $name === ''
            || Str::startsWith($name, ['admin.', 'user.', 'seo.', 'profile.', 'verification.', 'password.'])
            || in_array($name, ['login', 'register', 'logout', 'password.request', 'password.email', 'password.reset', 'password.update'], true);
    }

    private function streamIndex(int $totalUrls): void
    {
        echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        echo '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach ($this->pageFilenames() as $filename) {
            echo "    <sitemap>\n";
            echo '        <loc>'.$this->xmlEscape($this->absoluteUrl($filename))."</loc>\n";
            echo '        <lastmod>'.$this->xmlEscape(now()->toAtomString())."</lastmod>\n";
            echo "    </sitemap>\n";
        }

        echo '</sitemapindex>';
    }

    private function streamUrlset(Collection $entries): void
    {
        echo $this->xmlDocument($entries);
    }

    private function xmlDocument(Collection $entries): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach ($entries as $entry) {
            $xml .= $this->xmlUrl(
                $entry['loc'],
                $entry['changefreq'] ?? 'weekly',
                (string) ($entry['priority'] ?? '0.8'),
                $entry['lastmod'] ?? null
            );
        }

        $xml .= '</urlset>';

        return $xml;
    }

    private function pageNumberForFilename(string $filename): ?int
    {
        foreach ($this->pageFilenames() as $index => $pageFilename) {
            if ($filename === $pageFilename) {
                return $index + 1;
            }
        }

        return null;
    }

    private function childFilename(int $page): string
    {
        $baseName = pathinfo($this->baseFilename(), PATHINFO_FILENAME);
        $filename = str_replace(['{base}', '{page}'], [$baseName, (string) $page], $this->childPattern());

        return $this->sanitizeFilename($filename);
    }

    private function isIndexFilename(string $filename): bool
    {
        return in_array($filename, ['sitemap.xml', $this->baseFilename()], true);
    }

    private function sanitizeFilename(string $filename): string
    {
        $baseName = pathinfo($filename, PATHINFO_FILENAME);
        $baseName = Str::slug($baseName ?: 'sitemap');

        return ($baseName ?: 'sitemap').'.xml';
    }

    private function xmlUrl(string $url, string $changeFrequency, string $priority, $lastModified = null): string
    {
        $url = $this->absoluteUrl($url);

        $xml = "    <url>\n";
        $xml .= '        <loc>'.$this->xmlEscape($url)."</loc>\n";

        $lastModified = $this->formatLastModified($lastModified);

        if ($lastModified !== null) {
            $xml .= '        <lastmod>'.$this->xmlEscape($lastModified)."</lastmod>\n";
        }

        $xml .= '        <changefreq>'.$this->xmlEscape($changeFrequency)."</changefreq>\n";
        $xml .= '        <priority>'.$this->xmlEscape($priority)."</priority>\n";
        $xml .= "    </url>\n";

        return $xml;
    }

    private function xmlEscape(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_COMPAT, 'UTF-8');
    }

    private function formatLastModified($lastModified): ?string
    {
        if ($lastModified instanceof DateTimeInterface) {
            return $lastModified->format(DateTimeInterface::ATOM);
        }

        if (is_string($lastModified) && trim($lastModified) !== '') {
            try {
                return Carbon::parse($lastModified)->toAtomString();
            } catch (\Throwable $exception) {
                return null;
            }
        }

        return null;
    }

    private function absoluteUrl(string $url): string
    {
        $baseUrl = $this->frontendBaseUrl();

        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $path = parse_url($url, PHP_URL_PATH) ?: '/';
            $query = parse_url($url, PHP_URL_QUERY);

            return $baseUrl.$path.($query ? '?'.$query : '');
        }

        return $baseUrl.'/'.ltrim($url, '/');
    }

    private function frontendBaseUrl(): string
    {
        try {
            if (Schema::hasTable('siteinfo')) {
                $frontendUrl = SiteInfo::query()->value('frontend_url');

                if (is_string($frontendUrl) && trim($frontendUrl) !== '') {
                    return rtrim($frontendUrl, '/');
                }
            }
        } catch (\Throwable $exception) {
            //
        }

        return rtrim(config('app.url'), '/');
    }
}
