<?php

namespace Sirius\Validation;

class RuleCollection extends \SplObjectStorage
{


    public function attach($rule, $data = null)
    {
        if ($this->contains($rule)) {
            return;
        }
        if ($rule instanceof Rule\Required) {
            $rules = array();
            foreach ($this as $r) {
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

    public function getHash($rule)
    {
        /* @var $rule Rule\AbstractValidator */
        return $rule->getUniqueId();
    }

}
