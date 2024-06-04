<?php

use Kirby\Cms\App as Kirby;
use Kirby\Filesystem\Dir;
use Kirby\Filesystem\F;
use Kirby\Toolkit\A;
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
    'translations' => A::keyBy(
        A::map(
            Dir::read($dir = kirby()->root('languages') . '/translations'),
            fn ($file) => A::merge(
                ['lang' => F::name($file)],
                Translation::load($dir . '/' . $file)
            )
        ),
        'lang'
    )
]);
