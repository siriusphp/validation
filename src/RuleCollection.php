<?php

declare(strict_types=1);

namespace Sirius\Validation;

use ReturnTypeWillChange;
use Sirius\Validation\Rule\AbstractRule;
use SplObjectStorage;

class RuleCollection extends SplObjectStorage
{
    #[ReturnTypeWillChange]
    public function attach(mixed $rule, mixed $data = null): void
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

    #[ReturnTypeWillChange]
    public function getHash($rule): string
    {
        /** @var AbstractRule $rule */
        return $rule->getUniqueId();
    }
}
