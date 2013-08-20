<?php

namespace Sirius\Validation;

use Sirius\Validation\Utils;
use Sirius\Validation\Helper;

class Validator {
	CONST ALL_RULES = '__all__';

	static protected $globalDefaultMessages = array(
		'_default' 		=> 'Value does not match validation criteria',
		'required' 		=> 'Required value',
		'email'			=> 'Value is not a valid email address',
	);

	protected $defaultMessages = array();

	protected $wasValidated = false;

	protected $conditions;

	protected $rules = array();

	protected $messages = array();

	static function getGlobalDefaultMessages() {
		return self::$globalDefaultMessages;
	}

	static function setGlobalDefaultMessages($rule, $message = null) {
		if (is_array($rule)) {
			foreach ($rule as $k => $v) {
				self::setGlobalDefaultMessages($k, $v);
			}
			return;
		}
		if ($message) {
			self::$globalDefaultMessages[$rule] = $message;
		}
	}

	function __construct($rules = null) {
		if (is_array($rules) or
			$rules instanceOf Traversable) {
			foreach ($rules as $rule) {
				call_user_func_array(array($this, 'add'), $rule);
			}
		}
	}

	protected function getDefaultErrorMessage($rule) {
		if (array_key_exists($rule, self::$globalDefaultMessages)) {
			return self::$globalDefaultMessages[$rule];
		}
		return self::$globalDefaultMessages['_default'];
	}

	protected function compileMessage($message) {
		if (is_array($message)) {
			return call_user_func_array('sprintf', $message);
		}
		return $message;
	}

	protected function getRuleId($rule) {
		$ruleId = $rule['name'];
		// certain rules can be applied multiple times with different parameters
		switch ($rule['name']) {
			case 'regex':
			case 'notRegex':
				$ruleId .= $rule['params'][0];
			break;
			case 'callback':
				if (!is_array($rule['params'][0])) {
					// the callback is a string like 'functionName' or 'Class::method'
					if (is_string($rule['params'][0])) {
						$ruleId .= $rule['params'][0];
					// the callback is an anonymous fucntion which PHP sees it as an object
					} elseif (is_object($rule['params'][0])) {
						$ruleId .= spl_object_hash($rule['params'][0]);
					}
				} else {
					// the callback is an array like array('ClassName', 'staticMethod')
					if (is_string($rule['params'][0][0])) {
						$ruleId .= $rule['params'][0][0] . '::' . $rule['params'][0][1];
					// the callback is an array like array($object, 'method')	
					} elseif (is_object($rule['params'][0][0])) {
						$ruleId .= spl_object_hash($rule['params'][0][0]) . '->' . $rule['params'][0][1];
					}
				}
			break;
		}
		return $ruleId;
	}

	function addCondition($name, $function) {
		if (!is_array($this->conditions)) {
			$this->conditions = array();
		}
		if (is_callable($function)) {
			$this->conditions[$name] = $function;
		} else {
			throw new \InvalidArgumentException(sprintf('The condition named "%s" is not callable', $name));
		}
		return $this;
	}

	function removeCondition($name) {
		if (is_array($this->conditions)
			and array_key_exists($name, $this->conditions)) {
			unset($this->conditions[$name]);
		}
		return $this;
	}

	function add($selector, $rule, $params = null, $message = null, $condition = null) {
		if (is_array($rule)) {
			foreach ($rule as $singleRule) {
				// make sure the rule is an array (the parameters of subsequent calls);
				$singleRule = is_array($singleRule) ? $singleRule : array($singleRule);
				array_unshift($singleRule, $selector);
				call_user_func_array(array($this, 'add'), $singleRule);
			}
			return $this;
		}
		if (is_string($rule)) {
			// rule was supplied like this 'required | email'
			if (strpos($rule, ' | ') !== false) {
				return $this->add($selector, explode(' | ', $rule));
			}
			// rule was supplied like this 'if(condition)length[2,10]error'
			if (strpos($rule, '[') !== false or strpos($rule, '(') !== false) {
				list($rule, $params, $message, $condition) = $this->parseRule($rule);
			}
		}
		if (!$message) {
			$message = $this->getDefaultErrorMessage($rule);
		}
		$rule = array(
			'name'		=> $rule,
			'params'	=> $params,
			'message'	=> $this->compileMessage($message),
			'condition' => $condition
		);
		$ruleId = $this->getRuleId($rule);
		if (!array_key_exists($selector, $this->rules)) {
			$this->rules[$selector] = array();
		}
		$this->rules[$selector][$ruleId] = $rule;
		return $this;
	}

	/**
	 * Remove validation rule
	 * @param  string  	$selector data selector
	 * @param  mixed 	$rule     rule name or true if all rules should be deleted for that selector
	 * @param  mixed  	$params   rule parameters, necessary for rules that depend on params for their ID
	 * @return self
	 */
	function remove($selector, $rule = true, $params = null) {
		if (!array_key_exists($selector, $this->rules)) {
			return $this;
		}
		if ($rule === true) {
			unset($this->rules[$selector]);
		} else {
			$ruleId = $this->getRuleId(array(
				'name' => $rule,
				'params' => $params ? $params : array()
			));
			if (array_key_exists($ruleId, $this->rules[$selector])) {
				unset($this->rules[$selector][$ruleId]);
			}
		}
		return $this;
	}

	protected function parseRule($ruleAsString) {
		$ruleAsString = trim($ruleAsString);
		$condition 	= '';
		$rule 		= '';
		$params 	= array();
		$message 	= '';
		if (strpos($ruleAsString, 'if(') === 0) {
			$firstClosedParanthesis = strpos($ruleAsString, ')');
			$condition = substr($ruleAsString, 3, $firstClosedParanthesis - 3);
			$ruleAsString = substr($ruleAsString, $firstClosedParanthesis + 1);
		}
		$openBracket = strpos($ruleAsString, '[');
		$closeBracket = strrpos($ruleAsString, ']');
		if (!$openBracket) {
			$rule = $ruleAsString;
		} else {
			$rule = substr($ruleAsString, 0, $openBracket);
			$params = explode(',', substr($ruleAsString, $openBracket + 1, $closeBracket - $openBracket));
			$message = substr($ruleAsString, $closeBracket + 1);
		}

		//echo $rule, var_dump($params, true), $message, $condition;
		return array($rule, $params, $message, $condition);
	}

	function setData($data) {
		if (!is_array($data)) {
			throw new \InvalidArgumentException('Data passed to validator is not an array');
		}
		$this->data = $data;
		$this->wasValidated = false;
		// reset messages
		$this->messages = array();
		return $this;
	}

	/**
	 * Performs the validation
	 * @param  array|false $data array to be validated
	 * @return boolean
	 */
	function validate($data = null) {
		if ($data !== null) {
			$this->setData($data);
		}
		// data was already validated, return the results immediately
		if ($this->wasValidated === true) {
			return $this->wasValidated and count($this->messages) === 0;
		}
		// since we validate the data that it is found below
		// we need to ensure the required items are there
		$this->ensureRequiredItems();
		foreach ($this->data as $item => $value) {
			$this->validateItem($item);
		}
		$this->wasValidated = true;
		return $this->wasValidated and count($this->messages) === 0;
	}

	function validateItem($item) {
		$value = Utils::arrayGetByPath($this->data, $item);
		if (is_array($value)) {
			foreach (array_keys($value) as $key) {
				$this->validateItem("{$item}[{$key}]");
			}
		} else {
			foreach ($this->rules as $selector => $selectorRules) {
				if ($this->itemMatchesSelector($item, $selector)) {
					foreach ($selectorRules as $ruleId => $rule) {
						if (!$this->valueSatisfiesRule($value, $rule)) {
							$this->addMessage($item, $rule['message']);
						}
						// if field is required and we have an error, 
						// do not continue with the rest of rules
						if ($ruleId === 'required'
							and array_key_exists($item, $this->messages)
							and count($this->messages[$item])) {
							break;
						}
					}
				}
			}
		}
	}

	protected function ensureRequiredItems() {
		foreach ($this->rules as $selector => $selectorRules) {
			if (array_key_exists('required', $selectorRules)) {
				$this->data = Utils::arraySetBySelector($this->data, $selector, null, false);
			}
		}
	}

	protected function valueSatisfiesRule($value, $rule) {
		// check if we have a pre-condition to be satisfied
		if ($rule['condition'] and array_key_exists($rule['condition'], $this->conditions)) {
			$conditionIsMet = call_user_func($this->conditions[$rule['condition']], $this->data);
			// do not proceed if the condition is not met
			if (!$conditionIsMet) {
				return true;
			}
		}
		if ($rule['name'] === 'required') {
			return (bool)$value;
		}
		$method = $rule['name'];
		if (Helper::methodExists($method)) {
			switch (count($rule['params'])) {
				case 0;
					return Helper::$method($value, $this->data);
				break;
				case 1;
					return Helper::$method($value, $rule['params'][0], $this->data);
				break;
				case 2;
					return Helper::$method($value, $rule['params'][0], $rule['params'][1], $this->data);
				break;
				case 3;
					return Helper::$method($value, $rule['params'][0], $rule['params'][1], $rule['params'][2], $this->data);
				break;
				default;
					$params = $rule['params'];
					array_unshift($params, $value);
					array_push($params, $this->data);
					return call_user_func_array(array('Sirius\Validation\Helper', $method), $params);
				break;
			}
		} else {
			throw new \InvalidArgumentException(sprintf('%s is not a valid method for validation', $method));
		}
		return true;
	}

	protected function itemMatchesSelector($item, $selector) {
		if (strpos($selector, '*')) {
			$regex = '/' . str_replace('*', '[^\]]+', str_replace(array('[', ']'), array('\[', '\]'), $selector)) . '/';
			#echo ($item . '|' . $regex . "\n");
			return preg_match($regex, $item);
		} else {
			return $item == $selector;
		}
	}

	/**
	 * Adds a messages to the list of error messages
	 * @param string $item    data identifier (eg: 'email', 'addresses[0][state]')
	 * @param string $message
	 * @return self
	 */
	function addMessage($item, $message = null) {
		if (!$message) {
			return;
		}
		if (!array_key_exists($item, $this->messages)) {
			$this->messages[$item] = array();
		}
		$this->messages[$item][] = $message;

		return $this;
	}

	/**
	 * Clears the messages of an item
	 * @param  string $item
	 * @return self
	 */
	function clearMessages($item = null) {
		if (is_string($item)) {
			if (array_key_exists($item, $this->messages)) {
				unset($this->messages[$item]);
			}
		} elseif ($item === null) {
			$this->messages = array();
		}
		return $this;
	}

	/**
	 * Returns all validation messages
	 * @param  string $item key of the messages array (eg: 'password', 'addresses[0][line_1]')
	 * @return array
	 */
	function getMessages($item = null) {
		if (is_string($item)) {
			return array_key_exists($item, $this->messages) ? $this->messages[$item] : array();
		}
		return $this->messages;
	}

}
