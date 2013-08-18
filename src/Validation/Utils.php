<?php

namespace Sirius\Validation;

class Utils  {

	protected static function getSelectorParts($selector) {
		$firstOpen = strpos($selector, '[');
		if ($firstOpen === false) {
			return array($selector, '');
		}
		$firstClose = strpos($selector, ']');
		$container = substr($selector, 0, $firstOpen);
		$subselector = substr($selector, $firstOpen + 1, $firstClose - $firstOpen - 1) . substr($selector, $firstClose + 1);
		return array($container, $subselector);
	}

	static function arrayGetByPath($array, $path) {
		list($container, $subselector) = self::getSelectorParts($path);
		if (!$subselector) {
			return array_key_exists($container, $array) ? $array[$container] : null;
		}
		return array_key_exists($container, $array) ? self::arrayGetByPath($array[$container], $subselector) : null;
	}

	/**
	 * Set values in the array by selector
	 * @example
	 * Utils::arraySetBySelector(array(), 'email', 'my@domain.com');
	 * Utils::arraySetBySelector(array(), 'addresses[0][line]', null);
	 * Utils::arraySetBySelector(array(), 'addresses[*][line]', null);
	 * @param  array $array
	 * @param  string $selector
	 * @param  mixed $value
	 * @param  bool $overwrite true if the $value should overwrite the existing value
	 * @return array
	 */
	static function arraySetBySelector($array, $selector, $value, $overwrite = false) {
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
}