<?php

namespace Sirius\Validation;

class Utils
{

    const PATH_ROOT = '/';

    protected static function getSelectorParts($selector)
    {
        $firstOpen = strpos($selector, '[');
        if ($firstOpen === false) {
            return array($selector, '');
        }
        $firstClose = strpos($selector, ']');
        $container = substr($selector, 0, $firstOpen);
        $subselector = substr($selector, $firstOpen + 1, $firstClose - $firstOpen - 1) . substr(
                $selector,
                $firstClose + 1
            );
        return array($container, $subselector);
    }

    /**
     * Retrieves an element from an array via its path
     * Path examples:
     *        key
     *        key[subkey]
     *        key[0][subkey]
     *
     * @param  array $array
     * @param  string $path
     * @return mixed
     */
    static function arrayGetByPath($array, $path = self::PATH_ROOT)
    {
        $path = trim($path);
        if (!$path || $path == self::PATH_ROOT) {
            return $array;
        }
        // fix the path in case it was provided as `[item][subitem]`
        if (strpos($path, '[') === 0) {
            $path = preg_replace('/]/', '', ltrim($path, '['), 1);
        }

        list($container, $subpath) = self::getSelectorParts($path);
        if ($subpath === '') {
            return array_key_exists($container, $array) ? $array[$container] : null;
        }
        return array_key_exists($container, $array) ? self::arrayGetByPath($array[$container], $subpath) : null;
    }

    /**
     * Set values in the array by selector
     *
     * @example
     * Utils::arraySetBySelector(array(), 'email', 'my@domain.com');
     * Utils::arraySetBySelector(array(), 'addresses[0][line]', null);
     * Utils::arraySetBySelector(array(), 'addresses[*][line]', null);
     *
     * @param  array $array
     * @param  string $selector
     * @param  mixed $value
     * @param  bool $overwrite true if the $value should overwrite the existing value
     * @return array
     */
    static function arraySetBySelector($array, $selector, $value, $overwrite = false)
    {
        // make sure the array is an array in case we got here through a subsequent call
        // arraySetElementBySelector(array(), 'item[subitem]', 'value');
        // will call
        // arraySetElementBySelector(null, 'subitem', 'value');
        if (!is_array($array)) {
            $array = array();
        }
        list($container, $subselector) = self::getSelectorParts($selector);
        if (!$subselector) {
            if ($container !== '*') {
                if ($overwrite == true or !array_key_exists($container, $array)) {
                    $array[$container] = $value;
                }
            }
            return $array;
        }

        // if we have a subselector the $array[$container] must be an array
        if ($container !== '*' and !array_key_exists($container, $array)) {
            $array[$container] = array();
        }
        // we got here through something like *[subitem]
        if ($container === '*') {
            foreach ($array as $key => $v) {
                $array[$key] = self::arraySetBySelector($array[$key], $subselector, $value, $overwrite);
            }
        } else {
            $array[$container] = self::arraySetBySelector($array[$container], $subselector, $value, $overwrite);
        }

        return $array;
    }

    static function arrayGetBySelector($array, $selector)
    {
        if (strpos($selector, '[*]') === false) {
            return array(
                $selector => self::arrayGetByPath($array, $selector)
            );
        }
        $result = array();
        list($preffix, $suffix) = explode('[*]', $selector, 2);

        $base = self::arrayGetByPath($array, $preffix);
        if (!is_array($base)) {
            $base = array();
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
                    $result["{$preffix}[{$itemKey}]{$suffix}"] = self::arrayGetByPath($itemValue, $suffix);
                }
            }
        }
        return $result;
    }
}
