<?php

namespace JanHerman\Utils;

use Closure;
use Kirby\Cms\App;
use Kirby\Cms\Page;

/**
 * Helper class for customizing the panel menu
 *
 * Based on original work by Lukas Kleinschmidt & Tobias Möritz
 * https://gist.github.com/lukaskleinschmidt/247a957ebcde66899757a16fead9a039
 * https://github.com/tobimori/kirby-spielzeug/blob/main/classes/Menu.php
 */
class Menu
{
    protected static array $pages = [];

    protected static string $path;

    /**
     * Returns the current panel request path
     */
    public static function path(): string
    {
        return static::$path ??= App::instance()->request()->path()->toString();
    }

    /**
     * Internal method to determine current state
     */
    protected static function isCurrent(string|null $link, array ...$ignore): bool
    {
        if ($link && !str_contains(static::path(), $link)) {
            return false;
        }

        foreach (array_merge(...$ignore) as $page) {
            if (str_contains(static::path(), $page)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns the panel.menu option for a specific link or page
     */
    public static function page(string|array $label = null, string $icon = null, string|Page $link = null, Closure|bool $current = null): array
    {
        if ($link instanceof Page) {
            $page = $link;
            $link = $link->panel()->path();
        }

        if (is_null($link)) {
            return [];
        }

        $data = [
            'label' => $label ?? ($page->title()->value() ?? ''),
            'link' => static::$pages[] = $link,
            'current' => $current ?? fn () => static::isCurrent($link),
        ];

        if ($icon) {
            $data['icon'] = $icon;
        }

        return $data;
    }

    /**
     * Returns the panel.menu option for a dialog
     */
    public static function dialog(string|array $label = null, string $icon = null, string $dialog = null): array
    {
        if (is_null($dialog)) {
            return [];
        }

        $data = [
            'label' => $label,
            'dialog' => $dialog,
        ];

        if ($icon) {
            $data['icon'] = $icon;
        }

        return $data;
    }

    /**
     * Returns the site panel.menu option, ignores all custom pages
     */
    public static function site(string $label = null, string $icon = null): array
    {
        $data = [
            'current' => fn (string $id = null) => $id === 'site' && static::isCurrent(null, static::$pages),
        ];

        if ($label) {
            $data['label'] = t($label, $label);
        }

        if ($icon) {
            $data['icon'] = $icon;
        }

        return $data;
    }
}
