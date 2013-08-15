<?php

namespace Sirius\Validation;

class Validator {
	CONST ALL_RULES = '__all__';

	protected $conditions;

	protected $rules = array();

	protected $messages = array();

	function __construct($rules = null) {
		if (is_array($rules) or
			$rules instanceOf Traversable) {
			foreach ($rules as $rule) {
				call_user_func_array(array($this, 'add'), $rule);
			}
		}
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
				// the callback is a string like 'functionName' or 'Class::method'
				if (count($rule['params']) === 1) {
					$ruleId .= $rule['params'][0];
				// the callback is an array like array('ClassName', 'staticMethod')
				} elseif (count($rule['params']) === 2 and is_string($rule['params'][0])) {
					$ruleId .= $rule['params'][0] . ':' . $rule['params'][1];
				// the callback is an array like array($object, 'method')	
				} elseif (count($rule['params']) === 2 and is_object($rule['params'][0])) {
					$ruleId .= get_class($rule['params'][0]) . '->' . $rule['params'][1];
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

	function remove($selector, $rule = self::ALL_RULES) {

		return $this;
	}

	function validate($data) {
		// reset messages
		$this->messages = array();
	}

	function validateItem($data, $selector) {

	}

	function getMessages() {

	}

	function getMessage($item) {

	}
}
