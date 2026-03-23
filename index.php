<?php

use Kirby\Cms\App as Kirby;
use Kirby\Exception\Exception;
use Kirby\Toolkit\Str;
use JanHerman\Utils\Embed;
use JanHerman\Utils\Translation;

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('jan-herman/utils', [
    /**
     * Field Methods
     */
    'fieldMethods' => [
        'unhtml' => function ($field) {
            return Str::unhtml($field->toString());
        }
    ],
    /**
     * Validators
     */
    'validators' => [
        'youtubeUrl' => function ($value): bool {
            $video_id = Embed::youtubeId($value);

            if ($video_id === null) {
                throw new Exception('\'' . $value . '\' is not a valid YouTube URL.');
            }

            return (bool) $video_id;
        },
        'vimeoUrl' => function ($value): bool {
            $video_id = Embed::vimeoId($value);

            if ($video_id === null) {
                throw new Exception('\'' . $value . '\' is not a valid Vimeo URL.');
            }

            return (bool) $video_id;
        },
    ],
    /**
     * Automatically load translations from `site/translations` folder
     */
    'translations' => Translation::loadDir(kirby()->root('languages') . '/translations'),
]);


// Helper functions
if (!function_exists('v')) {
    function v(string $url): string
    {
        $relative_url = parse_url($url, PHP_URL_PATH);
        $asset = asset($relative_url);

        if ($asset->exists()) {
            return $url . '?v=' . filemtime($asset->root());
        }

        return $url;
    }
}
