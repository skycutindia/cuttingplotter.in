<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    public function all(): array
    {
        return Cache::remember('site_settings', 3600, function () {
            return Setting::query()->pluck('value', 'key')->toArray();
        });
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $settings = $this->all();

        return $settings[$key] ?? $default;
    }

    public function setMany(array $settings, string $group = 'general'): void
    {
        foreach ($settings as $key => $value) {
            Setting::set($key, $value, $group);
        }

        Cache::forget('site_settings');
    }

    public function group(string $group): array
    {
        return Setting::query()->where('group', $group)->pluck('value', 'key')->toArray();
    }
}
