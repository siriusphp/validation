<?php

namespace Sirius\Validation;

use Sirius\Validation\Rule\AbstractValidator;

class RuleCollection implements \Iterator, \Countable {
    /**
     * Validation rules
     * @var array
     */
    protected $rules = array();
    
    /**
     * Iterator pointer
     * @var int
     */
    protected $current = 0;

    public function add(AbstractValidator $rule) {
        if ($this->has($rule)) {
            return $this;
        }
        if ($rule instanceof Rule\Required) {
            array_unshift($this->rules, $rule);
        } else {
            array_push($this->rules, $rule);
        }
        return $this;
    }
    
    /**
     * Verify if a specific selector has a validator associated with it
     *
     * @param \Sirius\Validation\Rule\AbstractValidator $rule            
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