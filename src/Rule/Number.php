<?php
namespace Sirius\Validation\Rule;

class Number extends AbstractRule
{
    const MESSAGE = 'This input must be a number';
    const LABELED_MESSAGE = '{label} must be a number';

    public function validate($value, $valueIdentifier = null)
    {
        $this->value   = $value;
        $this->success = (bool) filter_var($value, FILTER_VALIDATE_FLOAT) || (string) $value === '0';

        return $this->success;
    }
}
