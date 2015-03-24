<?php
namespace Sirius\Validation\Rule;

class Number extends AbstractValidator
{

    protected static $defaultMessageTemplate = 'This input must be a number';

    public function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;
        $this->success = (bool)filter_var($value, FILTER_VALIDATE_FLOAT) || (string) $value === '0';
        return $this->success;
    }
}
