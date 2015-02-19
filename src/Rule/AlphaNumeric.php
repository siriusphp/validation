<?php
namespace Sirius\Validation\Rule;

class AlphaNumeric extends AbstractValidator
{

    protected static $defaultMessageTemplate = 'This input can contain only letters and digits';

    public function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;
        $this->success = (bool)ctype_alnum((string)str_replace(' ', '', $value));
        return $this->success;
    }
}
