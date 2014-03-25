<?php

namespace Sirius\Validation;

use Sirius\Validation\Rule\AbstractValidator;

class RuleCollection extends \SplObjectStorage {
    
    
    function attach($rule, $data = null) {
        if ($this->contains($rule)) {
            return;
        }
        if ($rule instanceof Rule\Required) {
            $rules = array();
            foreach ($this as $k => $r) {
                $rules[] = $r;
                $this->detach($r);
            }
            array_unshift($rules, $rule);
            foreach ($rules as $r) {
                parent::attach($r);
            }
            return;
        }
        return parent::attach($rule);
    }
    
    function getHash($rule) {
        return $rule->getUniqueId();
    }

}