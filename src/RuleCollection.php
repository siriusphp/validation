<?php
declare(strict_types=1);

namespace Sirius\Validation;

class RuleCollection extends \SplObjectStorage
{
    public function attach($rule, $data = null)
    {
        if ($this->contains($rule)) {
            return;
        }
        if ($rule instanceof Rule\Required) {
            $rules = [];
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
        /* @var $rule Rule\AbstractRule */
        return $rule->getUniqueId();
    }
}
