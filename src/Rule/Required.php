<?php
namespace Sirius\Validation\Rule;

class Required extends AbstractValidator
{

    protected static $defaultMessageTemplate = 'This field is required';

    public function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;
        $this->success = ($value !== null && $value !== '');
        return $this->success;
    }
}
