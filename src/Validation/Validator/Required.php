<?php
namespace Sirius\Validation\Validator;

class Required extends AbstractValidator
{

    protected static $defaultMessageTemplate = 'This field is required';

    function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;
        $this->success = ($value !== null || trim($value) !== '');
        return $this->success;
    }
}