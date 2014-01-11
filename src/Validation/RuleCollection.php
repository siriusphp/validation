<?php

namespace Sirius\Validation;

use Sirius\Validation\Validator\AbstractValidator as ValidatorRule;
use Sirius\Validation\Validator\Required as RequiredRule;
use Sirius\Validation\Validator\AbstractValidator;

class RuleCollection implements \Iterator, \Countable {
    const MODE_APPEND = 'append';
    const MODE_PREPEND = 'prepend';
    
    protected $messages = array();
    
    protected $rules = array();
    
    protected $current = 0;

    public function add(ValidatorRule $rule) {
        if ($this->has($rule)) {
            return $this;
        }
        if ($rule instanceof RequiredRule) {
            array_unshift($this->rules, $rule);
        } else {
            array_push($this->rules, $rule);
        }
        return $this;
    }
    
    /**
     * Verify if a specific selector has a validator associated with it
     *
     * @param \Sirius\Validation\Validator\AbstractValidator $rule            
     * @return boolean
     */
    public function has(AbstractValidator $rule) {
        foreach($this->rules as $r) {
            if ($r->getUniqueId() == $rule->getUniqueId()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Remove a rule from the list
     * 
     * @param AbstractValidator $rule
     * @return \Sirius\Validation\RuleCollection
     */
    public function remove(AbstractValidator $rule) {
        foreach($this->rules as $k => $r) {
            if ($r->getUniqueId() == $rule->getUniqueId()) {
                unset($this->rules[$k]);
            }
        }
        return $this;
    }
    
    public function validate($value, $valueIdentifier = null, DataWrapper\WrapperInterface $context = null) {
        $this->messages = array();
        $isRequired = false;
        foreach ($this->rules as $rule) {
            if ($rule instanceof RequiredRule) {
                $isRequired = true;
                break;
            }
        }
        foreach ($this->rules as $rule) {
            $rule->setContext($context);
            if (! $rule->validate($value, $valueIdentifier)) {
                $this->addMessage($rule->getMessage());
            }
            // if field is required and we have an error,
            // do not continue with the rest of rules
            if ($isRequired && count($this->messages)) {
                break;
            }
        }
        return count($this->messages) === 0;
    }
    
    public function getMessages() {
        return $this->messages;
    }

    public function addMessage($message) {
        array_push($this->messages, $message);
        return $this;
    }
    
    public function current() {
        return isset($this->rules[$this->current]) ? $this->rules[$this->current] : null;
    }
    
    public function key() {
        return $this->current;
    }
    
    public function next() {
        $this->current++;
    }
    
    public function valid() {
        return isset($this->rules[$this->current]);
    }
    
    public function rewind() {
        $this->current = 0;
    }
    
    public function count() {
        return count($this->rules);
    }
}