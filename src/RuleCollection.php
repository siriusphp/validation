<?php

declare(strict_types=1);

namespace Sirius\Validation;

use SplObjectStorage;
use ReturnTypeWillChange;

class RuleCollection extends SplObjectStorage
{
    /**
     * @param null|object $rule
     * @param null|mixed  $data
     */
    #[ReturnTypeWillChange]
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

        parent::attach($rule);
    }

    /**
     * @param object $rule
     */
    #[ReturnTypeWillChange]
    public function getHash($rule)
    {
        /* @var $rule Sirius\Validation\Rule\AbstractRule */
        return $rule->getUniqueId();
    }
}
