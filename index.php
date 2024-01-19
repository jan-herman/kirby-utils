<?php

use Kirby\Cms\App as Kirby;
use Kirby\Toolkit\Str;

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('jan-herman/utils', [
    'fieldMethods' => [
        'unhtml' => function ($field) {
            return Str::unhtml($field->toString());
        }
    ]
]);
