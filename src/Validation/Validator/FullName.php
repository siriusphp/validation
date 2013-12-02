<?php
namespace Sirius\Validation\Validator;

class FullName extends AbstractValidator
{

    protected static $defaultMessageTemplate = 'This input is not a valid full name (first name and last name)';

    function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;
        // the space shouldn't be the second letter (ex: F Name) nor the second last (ex: First N)
        $this->success = ((strlen($value) >= 6 and strpos($value, ' ') !== false and strpos($value, ' ') != 1 and strrpos($value, ' ') != strlen($value) - 2));
        return $this->success;
    }
}