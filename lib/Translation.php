<?php

namespace JanHerman\Utils;

use Kirby\Data\Yaml;
use Kirby\Exception\Exception;
use Kirby\Filesystem\Dir;
use Kirby\Filesystem\F;
use Kirby\Toolkit\Str;

class Translation
{
    /**
     * Loads translation files from a directory and combines them by language.
     *
     * @param string $dir The directory containing the translation files.
     * @return array An associative array where keys are language codes and values are translations.
     */
    public static function loadDir(string $dir): array
    {
        $translations = [];
        $files = Dir::index($dir, true, null, $dir);

        foreach ($files as $path) {
            if (F::exists($path) === false) {
                continue;
            }

            $filename = F::name($path);
            $lang = str_contains($filename, '.') ? Str::after($filename, '.') : $filename;
            $file_translations = Translation::load($path);

            if (isset($translations[$lang])) {
                $translations[$lang] = array_merge($translations[$lang], $file_translations);
            } else {
                $translations[$lang] = $file_translations;
            }
        }

        return $translations;
    }

    /**
     * Loads a YAML file and returns a flattened array of translations.
     *
     * @param string $path The file path to the YAML file.
     * @return array The flattened array of translations.
     * @throws Exception If the file cannot be read or the YAML cannot be decoded.
     */
    public static function load(string $path): array
    {
        if (!F::exists($path)) {
            throw new Exception('File not found: $path');
        }

        $yaml = F::read($path);
        if ($yaml === false) {
            throw new Exception('Unable to read file: $path');
        }

        $array = Yaml::decode($yaml);

        if (!is_array($array)) {
            throw new Exception('Invalid YAML content in file: $path');
        }

        return self::flatten($array);
    }

    /**
     * Flattens an nested array of translations.
     *
     * The scheme used is:
     *   'key' => ['key2' => ['key3' => 'value']]
     * Becomes:
     *   'key.key2.key3' => 'value'
     *
     * @param array $array The nested array of translations.
     * @param string $prefix The prefix for the keys (used for recursion).
     * @return array The flattened array of translations.
     */
    public static function flatten(array $array): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            if (\is_array($value)) {
                foreach (self::flatten($value) as $k => $v) {
                    if (null !== $v) {
                        $result[$key.'.'.$k] = $v;
                    }
                }
            } elseif (null !== $value) {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}
