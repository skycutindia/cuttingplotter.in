<?php

namespace App\Http\Controllers;

use App\Services\SeoService;
use App\Services\SettingsService;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __construct(
        protected SeoService $seo,
        protected SettingsService $settings
    ) {}

    public function sitemap(): Response
    {
        return response($this->seo->getSitemap(), 200, [
            'Content-Type' => 'application/xml',
        ]);
    }

    public function robots(): Response
    {
        $content = $this->settings->get(
            'robots_txt',
            "User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /login\nSitemap: ".url('/sitemap.xml')
        );

        return response($content, 200, [
            'Content-Type' => 'text/plain',
        ]);
    }
}
