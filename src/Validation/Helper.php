<?php

namespace Sirius\Validation;

class Helper  {
	protected static $methods = array();
	
	static function addMethod($ruleName, $callback) {
		if (is_callable($callback)) {
			self::$methods[$ruleName] = $callback;
			return true;
		}
		return false;
	}

	static function __callStatic($name, $arguments) {
		if (array_key_exists($name, self::$methods)) {
			return call_user_func_array(self::$methods[$name], $arguments);
		}
		throw new \InvalidArgumentException(sprintf('Validation method "%s" does not exist', $name));
	}
	
	static protected function arrayGetByPath($array, $path) {
		$firstOpen = strpos($path, '[');
		if ($firstOpen === false) {
			return array_key_exists($path, $array) ? $array[$path] : null;
		}
		$prefix = substr($path, 0, $firstOpen);
		$firstClose = strpos($path, ']');
		$newPath = substr($path, $firstOpen + 1, $firstClose - $firstOpen - 1) . substr($path, $firstClose + 1);
		return array_key_exists($prefix, $array) ? self::arrayGetByPath($array[$prefix], $newPath) : null;
	}

	static function required($value) {
		return $value !== null and trim($value) !== '' and $value !== false and $value !== 0;
	}
	static function truthy($value) {
		return $value == true;
	}
	static function falsy($value) {
		return $value == false;
	}
	static function number($value) {
		return $value == '0' or is_numeric($value);
	}
	static function integer($value) {
		return $value == '0' or (int)$value == $value;
	}
	static function lessThan($value, $max) {
		return ($max != null and $value <= $max);		
	}
	static function greaterThan($value, $min) {
		return ($min != null and $value >= $min);		
	}
	static function between($value, $min, $max) {
		return self::lessThan($value, $max) and self::greaterThan($value, $min);
	}
	static function exactly($value, $otherValue) {
		return $value == $otherValue;
	}
	static function not($value, $otherValue) {
		return !self::exactly($value, $otherValue);
	}
	static function utf8alpha($value) {
		return (bool)preg_match('/^\pL++$/uD', (string)$value);
	}
	static function alpha($value) {
		return ctype_alpha((string)str_replace(' ', '', $value));
	}
	static function utf8alphanumeric($value) {
		return (bool)preg_match('/^[\pL\pN]++$/uD', (string)$value);
	}
	static function alphanumeric($value) {
		return ctype_alnum((string)str_replace(' ', '', $value));
		
	}
	static function utf8alphanumhyphen($value) {
		return (bool)preg_match('/^[-\pL\pN_]++$/uD', (string)$value);
	}
	static function alphanumhyphen($value) {
		return ctype_alnum((string)str_replace(array(' ', '_', '-'), '', $value));		
	}
	static function minLength($value, $min) {
		return self::length($value, $min, null);
	}
	static function maxLength($value, $max) {
		return self::length($value, null, $max);
	}
	static function length($value, $min, $max) {
		if ($min != null and strlen($value) < $min) {
			return false;
		}
		if ($max != null and strlen($value) > $max) {
			return false;
		}

		return true;
	}

	static function setMinSize($value, $min) {
		return count($value) >= $min;
	}

	static function setMaxSize($value, $max) {
		return count($value) <= $max;
	}

	static function setSize($value, $min, $max) {
		return self::setMinSize($value, $min) and self::setMaxSize($value, $max);
	}

	static function in($value, $values) {
		return in_array($value, $values);		
	}
	static function notIn($value, $values) {
		return !self::in($value, $values);
	}
	static function regex($value, $pattern) {
		return (bool)preg_match($pattern, $value);
	}
	static function notRegex($value, $pattern) {
		return !self::regex($value, $pattern);
	}
	static function equalTo($value, $otherElement, $context) {
		return $value == self::arrayGetByPath($context, $otherElement);
	}
	
	static function getTimestampFromFormatedString($string, $format) {
		$result = date_parse_from_format($format, $string);
		return mktime((int)$result['hour']
			, (int)$result['minute']
			, (int)$result['fraction']
			, (int)$result['month']
			, (int)$result['day']
			, (int)$result['year']);
	}
	
	static function date($value, $format = 'Y-m-d') {
		// if $format is array it is the context
		if (is_array($format)) {
			$format = 'Y-m-d';
		}
		return $value == date($format, self::getTimestampFromFormatedString($value, $format));
	}
	
	static function dateTime($value, $format = 'Y-m-d H:i:s') {
		// if $format is array it is the context
		if (is_array($format)) {
			$format = 'Y-m-d H:i:s';
		}
		return self::date($value, $format);
	}

	static function time($value, $format = 'H:i:s') {
		// if $format is array it is the context
		if (is_array($format)) {
			$format = 'H:i:s';
		}
		return self::date($value, $format);
	}

	static function website($value) {
		return self::regex($value, '@^(http|https)\://[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(:[a-zA-Z0-9]*)?/?([a-zA-Z0-9\-\._\?\,\'/\\\+&amp;%\$#\=~!])*$@');
	}

	static function url($str) {
		return (bool)filter_var($str, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED);
	}

	/**
	 * Test if a variable is a valid IP address
	 *
	 * @param 	string $str
	 * @return	bool
	 */
	static function ip($ip) {
		// Do not allow private and reserved range IPs
		$flags = FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE;
		if (strpos($ip, ':') !== false) {
			return (bool)filter_var($ip, FILTER_VALIDATE_IP, $flags | FILTER_FLAG_IPV6);
		}
		return (bool)filter_var($ip, FILTER_VALIDATE_IP, $flags | FILTER_FLAG_IPV4);
	}

	static function email($value) {
		return (bool)self::regex((string)$value, '/^[-_a-z0-9\'+*$^&%=~!?{}]++(?:\.[-_a-z0-9\'+*$^&%=~!?{}]+)*+@(?:(?![-.])[-a-z0-9.]+(?<![-.])\.[a-z]{2,6}|\d{1,3}(?:\.\d{1,3}){3})(?::\d++)?$/iD');
	}

	/**
	 * Test if a variable is a full name
	 * Criterias: at least 6 characters, 2 words
	 * 
	 * @param 	mixed $str
	 * @return 	bool
	 */
	static function fullName($str) {
		// the space shouldn't be the second letter (ex: F Name) nor the second last (ex: First N)
		return (strlen($str) >= 6 and strpos($str, ' ') !== false and strpos($str, ' ') != 1 and strrpos($str, ' ') != strlen($str) - 2);
	}

	/**
	 * Test if the domain of an email address is available
	 *
	 * @param 	string $email
	 * @return	bool
	 */

	public static function emailDomain($email) {
		// Check if the email domain has a valid MX record
		return (bool)checkdnsrr(preg_replace('/^[^@]+@/', '', $email), 'MX');
	}

}