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
        'stripHtml' => function (Field $field): Field {
            $field->value = Str::unhtml($field->toString());

            return $field;
        },
        /**
         * @deprecated Use `$field->stripHtml()->toString()` or chain
         *             `$field->stripHtml()` with other field methods instead.
         */
        'unhtml' => function (Field $field): string {
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
     * Collection Methods
     */
    'collectionMethods' => [
        'inflate' => function (int $size = 50, bool $shuffle = false): self {
            // Only run in dev environment
            if (!kirby()->environment()->isLocal()) {
                trigger_error('\'$collection->inflate()\' method is not allowed in production environment.', E_USER_NOTICE);
                return $this;
            }

            $count = $this->count();

            // Invalid size or already large enough
            if ($this->isEmpty() || $size <= 0 || $count >= $size) {
                return $this;
            }

            $collection = $this;
            $needed = $size - $count;

            for ($i = 0; $i < $needed; $i++) {
                $item = $this->nth($i % $count);

                if (!$item) {
                    break;
                }

                $key = $item->id() . '-' . bin2hex(random_bytes(5));
                $collection = $collection->append($key, $item);
            }

            if ($shuffle === true) {
                $collection = $collection->shuffle();
            }

            return $collection;
        }
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
