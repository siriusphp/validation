<?php
declare(strict_types=1);

namespace Sirius\Validation\Util;

class RuleHelper
{
    /**
     * Method that parses the option variable and converts it into an array
     * You can pass anything to a validator like:
     * - a query string: 'min=3&max=5'
     * - a JSON string: '{"min":3,"max":5}'
     * - a CSV string: '5,true' (for this scenario the 'optionsIndexMap' property is required)
     *
     * @param mixed $options
     *
     * @return array
     * @throws \InvalidArgumentException
     */
    public static function normalizeOptions($options, array $optionsIndexMap = [])
    {
        if ('0' === $options && count($optionsIndexMap) > 0) {
            $options = [$optionsIndexMap[0] => '0'];
        }
        if (! $options) {
            return [];
        }

        if (is_array($options) && static::arrayIsAssoc($options)) {
            return $options;
        }

        $result = $options;
        if ($options && is_string($options)) {
            $startChar = substr($options, 0, 1);
            if ($startChar == '{') {
                $result = json_decode($options, true);
            } elseif (strpos($options, '=') !== false) {
                $result = static::parseHttpQueryString($options);
            } else {
                $result = static::parseCsvString($options, $optionsIndexMap);
            }
        }

        if (! is_array($result)) {
            throw new \InvalidArgumentException('Validator options should be an array, JSON string or query string');
        }

        return $result;
    }

    /**
     * Converts a HTTP query string to an array
     *
     * @param $str
     *
     * @return array
     */
    public static function parseHttpQueryString(string $str)
    {
        parse_str($str, $arr);

        return static::convertBooleanStrings($arr);
    }

    /**
     * Converts 'true' and 'false' strings to TRUE and FALSE
     *
     * @param $arr
     *
     * @return bool|array
     */
    public static function convertBooleanStrings($arr)
    {
        if (is_array($arr)) {
            return array_map([ __CLASS__, 'convertBooleanStrings'], $arr);
        }
        if ($arr === 'true') {
            return true;
        }
        if ($arr === 'false') {
            return false;
        }

        return $arr;
    }


    /**
     * Parses a CSV string and converts the result into an "options" array
     * (an associative array that contains the options for the validation rule)
     *
     * @param $str
     *
     * @param array $optionsIndexMap
     *
     * @return array
     */
    public static function parseCsvString($str, array $optionsIndexMap = [])
    {
        if (! isset($optionsIndexMap) || ! is_array($optionsIndexMap) || empty($optionsIndexMap)) {
            throw new \InvalidArgumentException(
                '`$optionsIndexMap` argument must be provided for CSV-type parameters'
            );
        }

        $options = explode(',', $str);
        $result  = [];
        foreach ($options as $k => $v) {
            if (! isset($optionsIndexMap[$k])) {
                throw new \InvalidArgumentException(sprintf(
                    '`$optionsIndexMap` for the validator is missing the %s index',
                    $k
                ));
            }
            $result[$optionsIndexMap[$k]] = $v;
        }

        return static::convertBooleanStrings($result);
    }

    /**
     * Checks if an array is associative (ie: the keys are not numbers in sequence)
     *
     * @param array $arr
     *
     * @return bool
     */
    public static function arrayIsAssoc($arr)
    {
        return array_keys($arr) !== range(0, count($arr));
    }

    public static function normalizeFileSize($size)
    {
        $size = (string) $size;
        $units = ['B' => 0, 'K' => 1, 'M' => 2, 'G' => 3 ];
        $unit  = strtoupper(substr($size, strlen($size) - 1, 1));
        if (! isset($units[$unit])) {
            $normalizedSize = filter_var($size, FILTER_SANITIZE_NUMBER_INT);
        } else {
            $size           = filter_var(substr($size, 0, strlen($size) - 1), FILTER_SANITIZE_NUMBER_FLOAT);
            $normalizedSize = $size * pow(1024, $units[$unit]);
        }

        return $normalizedSize;
    }



    public static function normalizeImageRatio($ratio)
    {
        if (is_numeric($ratio) || $ratio == filter_var($ratio, FILTER_SANITIZE_NUMBER_FLOAT)) {
            return floatval($ratio);
        }
        if (strpos($ratio, ':') !== false) {
            list($width, $height) = explode(':', $ratio);

            return $width / $height;
        }

        return 0;
    }
}
