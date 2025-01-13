<?php

use Kirby\Cms\App as Kirby;
use Kirby\Toolkit\Str;
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
     * Automatically load translations from `site/translations` folder
     */
    'translations' => Translation::loadDir(kirby()->root('languages') . '/translations'),
]);
