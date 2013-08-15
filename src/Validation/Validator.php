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

			}
		}
	}

	protected function compileMessage($message) {

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
		return $this;
	}

	function remove($selector, $rule = self::ALL_RULES) {

		return $this;
	}

	function validate($data) {

	}

	function validateItem($data, $selector) {
		
	}

	function getMessages() {

	}

	function getMessage($item) {

	}
}
