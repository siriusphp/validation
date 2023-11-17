<?php
declare(strict_types=1);

namespace Sirius\Validation\Util;

class Arr
{

    /**
     * Constant that represents the root of an array
     */
    const PATH_ROOT = '/';

    /**
     * @return array<int,string>
     */
    protected static function getSelectorParts(string $selector): array
    {
        $firstOpen = strpos($selector, '[');
        if ($firstOpen === false) {
            return [$selector, ''];
        }
        $firstClose = strpos($selector, ']');
        $container = substr($selector, 0, $firstOpen);
        $subselector = substr($selector, $firstOpen + 1, $firstClose - $firstOpen - 1) . substr(
                $selector,
                $firstClose + 1
            );

        return [$container, $subselector];
    }

    /**
     * Retrieves an element from an array via its path
     * Path examples:
     *        key
     *        key[subkey]
     *        key[0][subkey]
     *
     * @param array<string,mixed> $array
     *
     * @return mixed
     */
    public static function getByPath(array $array, string $path = self::PATH_ROOT)
    {
        $path = trim($path);
        if (!$path || $path === self::PATH_ROOT) {
            return $array;
        }
        // fix the path in case it was provided as `[item][subitem]`
        if (str_starts_with($path, '[')) {
            $path = preg_replace('/]/', '', ltrim($path, '['), 1);
        }

        list($container, $subpath) = self::getSelectorParts($path ?? '');
        $subarray = $array[$container] ?? null;
        if ($subpath === '') {
            return $subarray;
        }

        return is_array($subarray) ? self::getByPath($subarray, $subpath) : null;
    }

    /**
     * Set values in the array by selector
     *
     * @param array<string,array|mixed> $array
     * @param bool $overwrite true if the $value should overwrite the existing value
     *
     * @return array<string,array|mixed>
     * @example
     * Arr::setBySelector($data, 'email', 'my@domain.com');
     * Arr::setBySelector($data, 'addresses[0][line]', null);
     * Arr::setBySelector($data, 'addresses[*][line]', null);
     *
     */
    public static function setBySelector(array $array, string $selector, mixed $value, bool $overwrite = false): array
    {
        list($container, $subselector) = self::getSelectorParts($selector);
        if (!$subselector) {
            if ($container !== '*') {
                if ($overwrite === true || !array_key_exists($container, $array)) {
                    $array[$container] = $value;
                }
            }

            return $array;
        }

        // if we have a subselector the $array[$container] must be an array
        if ($container !== '*' && !array_key_exists($container, $array)) {
            $array[$container] = [];
        }
        // we got here through something like *[subitem]
        if ($container === '*') {
            foreach ($array as $key => $v) {
                $array[$key] = self::setBySelector($array[$key], $subselector, $value, $overwrite);
            }
        } else {
            $array[$container] = self::setBySelector($array[$container], $subselector, $value, $overwrite);
        }

        return $array;
    }

    /**
     * Get values in the array by selector
     *
     * @param array<string,array|mixed> $array
     * @return  array<string,array|mixed>
     * @example
     * Arr::getBySelector($data, 'email');
     * Arr::getBySelector($data, 'addresses[0][line]');
     * Arr::getBySelector($data, 'addresses[*][line]');
     *
     */
    public static function getBySelector(array $array, string $selector): array
    {
        if (!str_contains($selector, '[*]')) {
            return [$selector => self::getByPath($array, $selector)];
        }
        $result = [];
        list($preffix, $suffix) = explode('[*]', $selector, 2);

        $base = self::getByPath($array, $preffix);
        if (!is_array($base)) {
            $base = [];
        }
        // we don't have a suffix, the selector was something like path[subpath][*]
        if (!$suffix) {
            foreach ($base as $k => $v) {
                $result["{$preffix}[{$k}]"] = $v;
            }
            // we have a suffix, the selector was something like path[*][item]
        } else {
            foreach ($base as $itemKey => $itemValue) {
                if (is_array($itemValue)) {
                    $result["{$preffix}[{$itemKey}]{$suffix}"] = self::getByPath($itemValue, $suffix);
                }
            }
        }

        return $result;
    }
}
