<?php
declare(strict_types=1);

namespace Sirius\Validation\Rule;

class Integer extends AbstractRule
{
    const MESSAGE = 'This input must be an integer number';
    const LABELED_MESSAGE = '{label} must be an integer number';

    public function validate(mixed $value, string $valueIdentifier = null): bool
    {
        $this->value = $value;
        $this->success = (bool)filter_var($value, FILTER_VALIDATE_INT) || $value === '0';

        return $this->success;
    }
}
