<?php

namespace JanHerman\Utils;

use Kirby\Toolkit\Config;
use Kirby\Http\Url as KirbyUrl;

class Url
{
    public static function isPanel(string|null $url = null): bool
    {
        $uri = KirbyUrl::toObject($url);

        $base_path = $uri->path()->first();
        $panel_slug = Config::get('panel.slug', 'panel');

        return $base_path === $panel_slug;
    }
}
