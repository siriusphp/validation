<?php
declare(strict_types=1);
namespace Sirius\Validation\Rule;

class Number extends AbstractRule
{
    const MESSAGE = 'This input must be a number';
    const LABELED_MESSAGE = '{label} must be a number';

    public function validate($value, string $valueIdentifier = null):bool
    {
        $this->value   = $value;
        $this->success = (bool) filter_var($value, FILTER_VALIDATE_FLOAT) || (string) $value === '0';

        return $this->success;
    }
}
