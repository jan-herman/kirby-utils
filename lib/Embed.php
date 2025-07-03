<?php

namespace JanHerman\Utils;

use Kirby\Toolkit\Str;

class Embed
{
    public static function type(string $url): string
    {
        if (Str::contains($url, 'youtu', true) === true) {
            return 'youtube';
        }

        if (Str::contains($url, 'vimeo', true) === true) {
            return 'vimeo';
        }

        return 'unknown';
    }

    public static function videoId(string $url): string|null
    {
        $type = static::type($url);

        if ($type === 'youtube') {
            return static::youtubeId($url);
        }

        if ($type === 'vimeo') {
            return static::vimeoId($url);
        }

        return null;
    }

    public static function youtubeId(string $url): string|null
    {
        $pattern = "/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user|shorts)\/))([^\?&\"'>]+)/";

        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    public static function vimeoId(string $url): string|null
    {
        $pattern = "/(?:http|https)?:?\/?\/?(?:www\.)?(?:player\.)?vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/(?:[^\/]*)\/videos\/|video\/|)(\d+)(?:|\/\?)/";

        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
