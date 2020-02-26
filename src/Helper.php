<?php
declare(strict_types=1);
namespace Sirius\Validation;

use Sirius\Validation\Util\Arr;

class Helper
{
    protected static $methods = [];

    public static function addMethod($ruleName, $callback)
    {
        if (is_callable($callback)) {
            self::$methods[$ruleName] = $callback;

            return true;
        }

        return false;
    }

    public static function methodExists($name)
    {
        return method_exists(__CLASS__, $name) || array_key_exists($name, self::$methods);
    }

    public static function __callStatic($name, $arguments)
    {
        if (array_key_exists($name, self::$methods)) {
            return call_user_func_array(self::$methods[$name], $arguments);
        }
        throw new \InvalidArgumentException(sprintf('Validation method "%s" does not exist', $name));
    }

    public static function callback($value, $callback, $context = [])
    {
        $validator = new Rule\Callback();
        $validator->setOption('callback', $callback);
        $validator->setContext($context);

        return $validator->validate($value);
    }

    public static function required($value)
    {
        return $value !== null && (!is_string($value) || trim($value) !== '');
    }

    public static function truthy($value)
    {
        return (bool) $value;
    }

    public static function falsy($value)
    {
        return ! static::truthy($value);
    }

    public static function number($value)
    {
        return $value == '0' || is_numeric($value);
    }

    public static function integer($value)
    {
        return $value == '0' || (int) $value == $value;
    }

    public static function lessThan($value, $max)
    {
        $validator = new Rule\LessThan(['max' => $max]);

        return $validator->validate($value);
    }

    public static function greaterThan($value, $min)
    {
        $validator = new Rule\GreaterThan(['min' => $min]);

        return $validator->validate($value);
    }

    public static function between($value, $min, $max)
    {
        $validator = new Rule\Between([
            'min' => $min,
            'max' => $max
        ]);

        return $validator->validate($value);
    }

    public static function exactly($value, $otherValue)
    {
        return $value == $otherValue;
    }

    public static function not($value, $otherValue)
    {
        return ! self::exactly($value, $otherValue);
    }

    public static function alpha($value)
    {
        $validator = new Rule\Alpha();

        return $validator->validate($value);
    }

    public static function alphanumeric($value)
    {
        $validator = new Rule\AlphaNumeric();

        return $validator->validate($value);
    }

    public static function alphanumhyphen($value)
    {
        $validator = new Rule\AlphaNumHyphen();

        return $validator->validate($value);
    }

    public static function minLength($value, $min)
    {
        $validator = new Rule\MinLength(['min' => $min]);

        return $validator->validate($value);
    }

    public static function maxLength($value, $max)
    {
        $validator = new Rule\MaxLength(['max' => $max]);

        return $validator->validate($value);
    }

    public static function length($value, $min, $max)
    {
        $validator = new Rule\Length([
            'min' => $min,
            'max' => $max
        ]);

        return $validator->validate($value);
    }

    public static function setMinSize($value, $min)
    {
        $validator = new Rule\ArrayMinLength(['min' => $min]);

        return $validator->validate($value);
    }

    public static function setMaxSize($value, $max)
    {
        $validator = new Rule\ArrayMaxLength(['max' => $max]);

        return $validator->validate($value);
    }

    public static function setSize($value, $min, $max)
    {
        $validator = new Rule\ArrayLength([
            'min' => $min,
            'max' => $max
        ]);

        return $validator->validate($value);
    }

    public static function inList($value, $values)
    {
        $validator = new Rule\InList(['list' => $values]);

        return $validator->validate($value);
    }

    public static function notInList($value, $values)
    {
        $validator = new Rule\NotInList(['list' => $values]);

        return $validator->validate($value);
    }

    public static function regex($value, $pattern)
    {
        $validator = new Rule\Regex(['pattern' => $pattern]);

        return $validator->validate($value);
    }

    public static function notRegex($value, $pattern)
    {
        $validator = new Rule\NotRegex(['pattern' => $pattern]);

        return $validator->validate($value);
    }

    public static function equalTo($value, $otherElementOrValue, $context = null)
    {
        if (func_num_args() == 2) {
            return $value == $otherElementOrValue;
        }

        return $value == Arr::getByPath($context, $otherElementOrValue);
    }

    public static function notEqualTo($value, $otherElementOrValue, $context = null)
    {
        if (func_num_args() == 2) {
            return $value != $otherElementOrValue;
        }

        return $value != Arr::getByPath($context, $otherElementOrValue);
    }

    public static function date($value, $format = 'Y-m-d')
    {
        $validator = new Rule\Date(['format' => $format]);

        return $validator->validate($value);
    }

    public static function dateTime($value, $format = 'Y-m-d H:i:s')
    {
        $validator = new Rule\DateTime(['format' => $format]);

        return $validator->validate($value);
    }

    public static function time($value, $format = 'H:i:s')
    {
        $validator = new Rule\Time(['format' => $format]);

        return $validator->validate($value);
    }

    public static function website($value)
    {
        $validator = new Rule\Website();

        return $validator->validate($value);
    }

    public static function url($value)
    {
        $validator = new Rule\Url();

        return $validator->validate($value);
    }

    /**
     * Test if a variable is a valid IP address
     *
     * @param string $value
     *
     * @return bool
     */
    public static function ipAddress($value)
    {
        $validator = new Rule\IpAddress();

        return $validator->validate($value);
    }

    public static function email($value)
    {
        $validator = new Rule\Email();

        return $validator->validate($value);
    }

    /**
     * Test if a variable is a full name
     * Criterias: at least 6 characters, 2 words
     *
     * @param mixed $value
     *
     * @return bool
     */
    public static function fullName($value)
    {
        $validator = new Rule\FullName();

        return $validator->validate($value);
    }

    /**
     * Test if the domain of an email address is available
     *
     * @param string $value
     *
     * @return bool
     */
    public static function emailDomain($value)
    {
        $validator = new Rule\EmailDomain();

        return $validator->validate($value);
    }
}
