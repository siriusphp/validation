<?php
namespace Sirius\Validation\Rule;

class Email extends AbstractRule
{
    const MESSAGE = 'This input must be a valid email address';

    const LABELED_MESSAGE = '{label} must be a valid email address';

    public function validate($value, $valueIdentifier = null)
    {
        $this->value   = $value;
        $this->success = (filter_var((string) $value, FILTER_VALIDATE_EMAIL) !== false);

        return $this->success;
    }
}
