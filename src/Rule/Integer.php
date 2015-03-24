<?php
namespace Sirius\Validation\Rule;

class Integer extends AbstractValidator
{

    protected static $defaultMessageTemplate = 'This input must be an integer number';

    public function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;
        $this->success = (bool)filter_var($value, FILTER_VALIDATE_INT) || (string) $value === '0';
        return $this->success;
    }
}
