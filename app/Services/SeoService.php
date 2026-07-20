<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class SeoService
{
    public function generateSitemap(): string
    {
        $urls = [];

        $urls[] = $this->urlEntry(url('/'), now(), 'daily', '1.0');
        $urls[] = $this->urlEntry(route('products.index'), now(), 'daily', '0.9');
        $urls[] = $this->urlEntry(route('brands.index'), now(), 'weekly', '0.8');
        $urls[] = $this->urlEntry(route('blog.index'), now(), 'daily', '0.8');
        $urls[] = $this->urlEntry(route('contact'), now(), 'monthly', '0.6');

        Product::where('is_active', true)->where('is_approved', true)
            ->select('slug', 'updated_at')
            ->chunk(200, function ($products) use (&$urls) {
                foreach ($products as $product) {
                    $urls[] = $this->urlEntry(route('products.show', $product->slug), $product->updated_at, 'weekly', '0.8');
                }
            });

        Brand::where('is_active', true)->select('slug', 'updated_at')->get()
            ->each(function ($brand) use (&$urls) {
                $urls[] = $this->urlEntry(route('brands.show', $brand->slug), $brand->updated_at, 'weekly', '0.7');
            });

        Category::where('is_active', true)->select('slug', 'updated_at')->get()
            ->each(function ($category) use (&$urls) {
                $urls[] = $this->urlEntry(route('products.index', ['category' => $category->slug]), $category->updated_at, 'weekly', '0.7');
            });

        Blog::where('status', 'published')->select('slug', 'updated_at', 'published_at')->get()
            ->each(function ($blog) use (&$urls) {
                $urls[] = $this->urlEntry(route('blog.show', $blog->slug), $blog->updated_at, 'monthly', '0.6');
            });

        Page::where('is_active', true)->select('slug', 'updated_at')->get()
            ->each(function ($page) use (&$urls) {
                $urls[] = $this->urlEntry(route('pages.show', $page->slug), $page->updated_at, 'monthly', '0.6');
            });

        $body = implode("\n", $urls);

        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
{$body}
</urlset>
XML;
    }

    public function getSitemap(): string
    {
        return Cache::remember('seo.sitemap', 3600, fn () => $this->generateSitemap());
    }

    public function clearSitemapCache(): void
    {
        Cache::forget('seo.sitemap');
    }

    public function productSchema(Product $product): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'description' => strip_tags($product->short_description ?? $product->description ?? ''),
            'sku' => $product->sku,
            'url' => route('products.show', $product->slug),
        ];

        if ($product->brand) {
            $schema['brand'] = ['@type' => 'Brand', 'name' => $product->brand->name];
        }

        if ($product->effective_price) {
            $schema['offers'] = [
                '@type' => 'Offer',
                'price' => (float) $product->effective_price,
                'priceCurrency' => 'INR',
                'availability' => $product->stock > 0
                    ? 'https://schema.org/InStock'
                    : 'https://schema.org/OutOfStock',
                'url' => route('products.show', $product->slug),
            ];
        }

        if ($product->faq) {
            $schema['subjectOf'] = [
                '@type' => 'FAQPage',
                'mainEntity' => collect($product->faq)->map(fn ($item) => [
                    '@type' => 'Question',
                    'name' => $item['question'] ?? '',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $item['answer'] ?? '',
                    ],
                ])->values()->all(),
            ];
        }

        return $schema;
    }

    public function breadcrumbSchema(array $items): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => collect($items)->values()->map(fn ($item, $i) => [
                '@type' => 'ListItem',
                'position' => $i + 1,
                'name' => $item['name'],
                'item' => $item['url'] ?? null,
            ])->all(),
        ];
    }

    public function organizationSchema(array $settings): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $settings['company_name'] ?? $settings['site_name'] ?? 'Cutting Plotter India',
            'url' => url('/'),
            'email' => $settings['company_email'] ?? null,
            'telephone' => $settings['company_phone'] ?? null,
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $settings['company_address'] ?? null,
                'addressCountry' => 'IN',
            ],
        ];
    }

    protected function urlEntry(string $loc, $lastmod, string $changefreq, string $priority): string
    {
        $date = $lastmod instanceof \Carbon\Carbon ? $lastmod->toW3cString() : now()->toW3cString();

        return "  <url>\n    <loc>{$loc}</loc>\n    <lastmod>{$date}</lastmod>\n    <changefreq>{$changefreq}</changefreq>\n    <priority>{$priority}</priority>\n  </url>";
    }
}
