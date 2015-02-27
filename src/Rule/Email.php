<?php
namespace Sirius\Validation\Rule;

class Email extends AbstractValidator
{
    protected static $defaultMessageTemplate = 'This input must be a valid email address';

    public function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;
        $this->success = (filter_var((string)$value, FILTER_VALIDATE_EMAIL) !== false);
        return $this->success;
    }
}
