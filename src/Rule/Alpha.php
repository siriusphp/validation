<?php
namespace Sirius\Validation\Rule;

class Alpha extends AbstractValidator
{

    protected static $defaultMessageTemplate = 'This input can contain only letters';

    public function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;
        $this->success = (bool)ctype_alpha((string)str_replace(' ', '', $value));
        return $this->success;
    }
}
