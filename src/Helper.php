<?php
namespace Sirius\Validation;

use Sirius\Validation\Util\Arr;

class Helper
{

    protected static $methods = array();

    static function addMethod($ruleName, $callback)
    {
        if (is_callable($callback)) {
            self::$methods[$ruleName] = $callback;
            return true;
        }
        return false;
    }

    static function methodExists($name)
    {
        return method_exists(__CLASS__, $name) or array_key_exists($name, self::$methods);
    }

    static function __callStatic($name, $arguments)
    {
        if (array_key_exists($name, self::$methods)) {
            return call_user_func_array(self::$methods[$name], $arguments);
        }
        throw new \InvalidArgumentException(sprintf('Validation method "%s" does not exist', $name));
    }

    static function callback($value, $callback, $context = array())
    {
        $validator = new Rule\Callback();
        $validator->setOption('callback', $callback);
        $validator->setContext($context);
        return $validator->validate($value);
    }

    static function required($value)
    {
        return $value !== null and trim($value) !== '';
    }

    static function truthy($value)
    {
        return $value == true;
    }

    static function falsy($value)
    {
        return $value == false;
    }

    static function number($value)
    {
        return $value == '0' or is_numeric($value);
    }

    static function integer($value)
    {
        return $value == '0' or (int)$value == $value;
    }

    static function lessThan($value, $max)
    {
        $validator = new Rule\LessThan(
            array(
                'max' => $max
            )
        );
        return $validator->validate($value);
    }

    static function greaterThan($value, $min)
    {
        $validator = new Rule\GreaterThan(
            array(
                'min' => $min
            )
        );
        return $validator->validate($value);
    }

    static function between($value, $min, $max)
    {
        $validator = new Rule\Between(
            array(
                'min' => $min,
                'max' => $max
            )
        );
        return $validator->validate($value);
    }

    static function exactly($value, $otherValue)
    {
        return $value == $otherValue;
    }

    static function not($value, $otherValue)
    {
        return !self::exactly($value, $otherValue);
    }

    static function alpha($value)
    {
        $validator = new Rule\Alpha();
        return $validator->validate($value);
    }

    static function alphanumeric($value)
    {
        $validator = new Rule\AlphaNumeric();
        return $validator->validate($value);
    }

    static function alphanumhyphen($value)
    {
        $validator = new Rule\AlphaNumHyphen();
        return $validator->validate($value);
    }

    static function minLength($value, $min)
    {
        $validator = new Rule\MinLength(
            array(
                'min' => $min
            )
        );
        return $validator->validate($value);
    }

    static function maxLength($value, $max)
    {
        $validator = new Rule\MaxLength(
            array(
                'max' => $max
            )
        );
        return $validator->validate($value);
    }

    static function length($value, $min, $max)
    {
        $validator = new Rule\Length(
            array(
                'min' => $min,
                'max' => $max
            )
        );
        return $validator->validate($value);
    }

    static function setMinSize($value, $min)
    {
        $validator = new Rule\ArrayMinLength(
            array(
                'min' => $min
            )
        );
        return $validator->validate($value);
    }

    static function setMaxSize($value, $max)
    {
        $validator = new Rule\ArrayMaxLength(
            array(
                'max' => $max
            )
        );
        return $validator->validate($value);
    }

    static function setSize($value, $min, $max)
    {
        $validator = new Rule\ArrayLength(
            array(
                'min' => $min,
                'max' => $max
            )
        );
        return $validator->validate($value);
    }

    static function in($value, $values)
    {
        $validator = new Rule\InList(
            array(
                'list' => $values
            )
        );
        return $validator->validate($value);
    }

    static function notIn($value, $values)
    {
        $validator = new Rule\NotInList(
            array(
                'list' => $values
            )
        );
        return $validator->validate($value);
    }

    static function regex($value, $pattern)
    {
        $validator = new Rule\Regex(
            array(
                'pattern' => $pattern
            )
        );
        return $validator->validate($value);
    }

    static function notRegex($value, $pattern)
    {
        $validator = new Rule\NotRegex(
            array(
                'pattern' => $pattern
            )
        );
        return $validator->validate($value);
    }

    static function equalTo($value, $otherElementOrValue, $context = null)
    {
        if (func_num_args() == 2) {
            return $value == $otherElementOrValue;
        }
        return $value == Arr::getByPath($context, $otherElementOrValue);
    }

    static function date($value, $format = 'Y-m-d')
    {
        $validator = new Rule\Date(
            array(
                'format' => $format
            )
        );
        return $validator->validate($value);
    }

    static function dateTime($value, $format = 'Y-m-d H:i:s')
    {
        $validator = new Rule\DateTime(
            array(
                'format' => $format
            )
        );
        return $validator->validate($value);
    }

    static function time($value, $format = 'H:i:s')
    {
        $validator = new Rule\Time(
            array(
                'format' => $format
            )
        );
        return $validator->validate($value);
    }

    static function website($value)
    {
        $validator = new Rule\Website();
        return $validator->validate($value);
    }

    static function url($value)
    {
        $validator = new Rule\Url();
        return $validator->validate($value);
    }

    /**
     * Test if a variable is a valid IP address
     *
     * @param string $value
     * @return bool
     */
    static function ip($value)
    {
        $validator = new Rule\IpAddress();
        return $validator->validate($value);
    }

    static function email($value)
    {
        $validator = new Rule\Email();
        return $validator->validate($value);
    }

    /**
     * Test if a variable is a full name
     * Criterias: at least 6 characters, 2 words
     *
     * @param mixed $value
     * @return bool
     */
    static function fullName($value)
    {
        $validator = new Rule\FullName();
        return $validator->validate($value);
    }

    /**
     * Test if the domain of an email address is available
     *
     * @param string $value
     * @return bool
     */
    public static function emailDomain($value)
    {
        $validator = new Rule\EmailDomain();
        return $validator->validate($value);
    }
}
