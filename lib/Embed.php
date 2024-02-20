<?php

namespace JanHerman\Utils;

use Kirby\Toolkit\Str;

class Embed
{
    public static function videoId(string $url): string|null
    {
        if (Str::contains($url, 'youtu', true) === true) {
            return static::youtubeId($url);
        }

        if (Str::contains($url, 'vimeo', true) === true) {
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
        $pattern = "/^(http|https)?:\/\/(www\.|player\.)?vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|video\/|)(\d+)(?:|\/\?)$/gmi";

        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
