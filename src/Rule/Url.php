<?php
namespace Sirius\Validation\Rule;

class Url extends AbstractValidator
{

    protected static $defaultMessageTemplate = 'This input is not a valid URL';

    public function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;
        $this->success = (bool)filter_var($value, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED);
        return $this->success;
    }
}
