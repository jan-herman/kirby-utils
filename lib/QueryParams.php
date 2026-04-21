<?php

namespace JanHerman\Utils;

use Kirby\Toolkit\A;

class QueryParams
{
    private array $params;
    private string $separator;

    /**
     * Build the params collection from input or the request params.
     */
    public function __construct(array|null $params = null, string $separator = ',')
    {
        $this->separator = $separator;
        $this->params = [];

        $params = $params ?? params();

        foreach ($params as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }
            foreach (explode($this->separator, $value) as $single_value) {
                $this->add($key, $single_value);
            }
        }
    }

    /**
     * Clone the params object.
     */
    public function clone(): self
    {
        return clone $this;
    }

    /**
     * Add a single value for a key if it's not already present.
     */
    public function add(string $key, string $value): void
    {
        if (in_array($value, $this->params[$key] ?? [], true)) {
            return;
        }

        $this->params[$key][] = $value;
    }

    /**
     * Remove a whole key or a single value for that key.
     */
    public function remove(string $key, string|null $value = null): void
    {

        if (!isset($this->params[$key])) {
            return;
        }

        if ($value === null) {
            unset($this->params[$key]);
            return;
        }

        $this->params[$key] = array_values(array_filter(
            $this->params[$key],
            fn($v) => $v !== $value
        ));

        if (empty($this->params[$key])) {
            unset($this->params[$key]);
        }
    }

    /**
     * Get all values for a key or a default.
     */
    public function values(string $key, array $default = []): array
    {
        return $this->params[$key] ?? $default;
    }

    /**
     * Return the internal params array.
     */
    public function all(): array
    {
        return $this->params;
    }

    /**
     * Check whether a key exists, or a specific value exists for that key.
     */
    public function has(string $key, string|null $value = null): bool
    {
        if ($value === null) {
            return isset($this->params[$key]);
        }

        if (!isset($this->params[$key])) {
            return false;
        }

        return in_array($value, $this->params[$key], true);
    }

    /**
     * Check whether there are no params.
     */
    public function isEmpty(): bool
    {
        return empty($this->params);
    }

    /**
     * Check whether there is at least one param.
     */
    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }

    /**
     * Get a key as a joined string or return a default.
     */
    public function get(string $key, string|null $default = null): string|null
    {
        if (!isset($this->params[$key])) {
            return $default;
        }

        return implode($this->separator, $this->params[$key]);
    }

    /**
     * Convert params to an array of joined strings.
     */
    public function toArray(): array
    {
        return array_map(function ($values) {
            return implode($this->separator, $values);
        }, $this->params);
    }

    /**
     * Return a new instance with an added value.
     */
    public function with(string $key, string $value): self
    {
        $clone = $this->clone();
        $clone->add($key, $value);
        return $clone;
    }

    /**
     * Return a new instance with a key or value removed.
     */
    public function without(string $key, string|null $value = null): self
    {
        $clone = $this->clone();
        $clone->remove($key, $value);
        return $clone;
    }

    /**
     * Return a new instance with exactly one value for a key.
     */
    public function one(string $key, string $value): self
    {
        $clone = $this->clone();
        $clone->params[$key] = [$value];
        return $clone;
    }

    /**
     * Return a new instance with a value toggled on/off.
     */
    public function toggle(string $key, string $value): self
    {
        $clone = $this->clone();

        if ($this->has($key, $value)) {
            $clone->remove($key, $value);
        } else {
            $clone->add($key, $value);
        }

        return $clone;
    }

    /**
     * Return a new instance containing only the given keys.
     */
    public function only(array|string $keys): self
    {
        $keys = A::wrap($keys);

        $clone = $this->clone();
        $allowed_keys = array_fill_keys($keys, true);
        $clone->params = array_intersect_key($clone->params, $allowed_keys);
        return $clone;
    }

    /**
     * Return a new instance without the given keys.
     */
    public function except(array|string $keys): self
    {
        $keys = A::wrap($keys);

        $clone = $this->clone();
        foreach ($keys as $key) {
            $clone->remove($key);
        }
        return $clone;
    }

    /**
     * Build a URL with the current params.
     */
    public function url(string|null $path = null, array $options = []): string
    {
        return urldecode(url($path, array_merge(['params' => $this->toArray()], $options)));
    }
}
