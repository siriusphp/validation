<?php
declare(strict_types=1);

namespace Sirius\Validation;

use Sirius\Validation\Util\Arr;

class Helper
{
    /**
     * @var array<string, callable|\Closure>
     */
    protected static array $methods = [];

    /**
     * @param callable|\Closure $callback
     */
    public static function addMethod(string $ruleName, mixed $callback): void
    {
        if (is_callable($callback)) {
            self::$methods[$ruleName] = $callback;
            return;
        }

        throw new \InvalidArgumentException(sprintf('Validation method "%s" is not callable', $ruleName)); // @phpstan-ignore-line
    }

    public static function methodExists(string $name): bool
    {
        return method_exists(__CLASS__, $name) || array_key_exists($name, self::$methods);
    }

    /**
     * @param array<int, mixed> $arguments
     */
    public static function __callStatic(string $name, array $arguments = []): mixed
    {
        if (array_key_exists($name, self::$methods)) {
            return call_user_func_array(self::$methods[$name], $arguments);
        }
        throw new \InvalidArgumentException(sprintf('Validation method "%s" does not exist', $name));
    }

    /**
     * @param array<string, mixed> $context
     */
    public static function callback(mixed $value, callable $callback, array $context = []): bool
    {
        $validator = new Rule\Callback();
        $validator->setOption('callback', $callback);
        $validator->setContext($context);

        return $validator->validate($value);
    }

    public static function required(mixed $value): bool
    {
        return $value !== null && (!is_string($value) || trim($value) !== '');
    }

    public static function truthy(mixed $value): bool
    {
        return (bool)$value;
    }

    public static function falsy(mixed $value): bool
    {
        return !static::truthy($value);
    }

    public static function number(mixed $value): bool
    {
        return $value == '0' || is_numeric($value);
    }

    public static function integer(mixed $value): bool
    {
        return $value == '0' || (int)$value == $value;
    }

    public static function lessThan(mixed $value, int|float $max): bool
    {
        $validator = new Rule\LessThan(['max' => $max]);

        return $validator->validate($value);
    }

    public static function greaterThan(mixed $value, int|float $min): bool
    {
        $validator = new Rule\GreaterThan(['min' => $min]);

        return $validator->validate($value);
    }

    public static function between(mixed $value, int|float $min, int|float $max): bool
    {
        $validator = new Rule\Between([
            'min' => $min,
            'max' => $max
        ]);

        return $validator->validate($value);
    }

    public static function exactly(mixed $value, mixed $otherValue): bool
    {
        return $value == $otherValue;
    }

    public static function not(mixed $value, mixed $otherValue): bool
    {
        return !self::exactly($value, $otherValue);
    }

    public static function alpha(mixed $value): bool
    {
        $validator = new Rule\Alpha();

        return $validator->validate($value);
    }

    public static function alphanumeric(mixed $value): bool
    {
        $validator = new Rule\AlphaNumeric();

        return $validator->validate($value);
    }

    public static function alphanumhyphen(mixed $value): bool
    {
        $validator = new Rule\AlphaNumHyphen();

        return $validator->validate($value);
    }

    public static function minLength(string $value, int $min): bool
    {
        $validator = new Rule\MinLength(['min' => $min]);

        return $validator->validate($value);
    }

    public static function maxLength(string $value, int $max): bool
    {
        $validator = new Rule\MaxLength(['max' => $max]);

        return $validator->validate($value);
    }

    public static function length(string $value, int $min, int $max): bool
    {
        $validator = new Rule\Length([
            'min' => $min,
            'max' => $max
        ]);

        return $validator->validate($value);
    }

    /**
     * @param array<int|string, mixed> $value
     */
    public static function setMinSize(array $value, int $min): bool
    {
        $validator = new Rule\ArrayMinLength(['min' => $min]);

        return $validator->validate($value);
    }

    /**
     * @param array<int|string, mixed> $value
     */
    public static function setMaxSize(array $value, int $max): bool
    {
        $validator = new Rule\ArrayMaxLength(['max' => $max]);

        return $validator->validate($value);
    }

    /**
     * @param array<int|string, mixed> $value
     */
    public static function setSize(array $value, int $min, int $max): bool
    {
        $validator = new Rule\ArrayLength([
            'min' => $min,
            'max' => $max
        ]);

        return $validator->validate($value);
    }

    /**
     * @param list<mixed> $values
     */
    public static function inList(mixed $value, array $values): bool
    {
        $validator = new Rule\InList(['list' => $values]);

        return $validator->validate($value);
    }

    /**
     * @param list<mixed> $values
     */
    public static function notInList(mixed $value, array $values): bool
    {
        $validator = new Rule\NotInList(['list' => $values]);

        return $validator->validate($value);
    }

    public static function regex(mixed $value, string $pattern): bool
    {
        $validator = new Rule\Regex(['pattern' => $pattern]);

        return $validator->validate($value);
    }

    public static function notRegex(mixed $value, string $pattern): bool
    {
        $validator = new Rule\NotRegex(['pattern' => $pattern]);

        return $validator->validate($value);
    }

    /**
     * @param array<string, mixed> $context
     */
    public static function equalTo(mixed $value, mixed $otherElementOrValue, array $context = []): bool
    {
        if (func_num_args() == 2) {
            return $value == $otherElementOrValue;
        }

        return $value == Arr::getByPath($context, $otherElementOrValue);
    }

    /**
     * @param array<string, mixed> $context
     */
    public static function notEqualTo(mixed $value, mixed $otherElementOrValue, array $context = []): bool
    {
        if (func_num_args() == 2) {
            return $value != $otherElementOrValue;
        }

        return $value != Arr::getByPath($context, $otherElementOrValue);
    }

    public static function date(mixed $value, string $format = 'Y-m-d'): bool
    {
        $validator = new Rule\Date(['format' => $format]);

        return $validator->validate($value);
    }

    public static function dateTime(mixed $value, string $format = 'Y-m-d H:i:s'): bool
    {
        $validator = new Rule\DateTime(['format' => $format]);

        return $validator->validate($value);
    }

    public static function time(mixed $value, string $format = 'H:i:s'): bool
    {
        $validator = new Rule\Time(['format' => $format]);

        return $validator->validate($value);
    }

    public static function website(mixed $value): bool
    {
        $validator = new Rule\Website();

        return $validator->validate($value);
    }

    public static function url(mixed $value): bool
    {
        $validator = new Rule\Url();

        return $validator->validate($value);
    }

    public static function ipAddress(mixed $value): bool
    {
        $validator = new Rule\IpAddress();

        return $validator->validate($value);
    }

    public static function email(mixed $value): bool
    {
        $validator = new Rule\Email();

        return $validator->validate($value);
    }

    /**
     * Test if a variable is a full name
     * Criterias: at least 6 characters, 2 words
     */
    public static function fullName(mixed $value): bool
    {
        $validator = new Rule\FullName();

        return $validator->validate($value);
    }

    /**
     * Test if the domain of an email address is available
     */
    public static function emailDomain(mixed $value): bool
    {
        $validator = new Rule\EmailDomain();

        return $validator->validate($value);
    }
}
