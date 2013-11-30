<?php
namespace Sirius\Validation\Validator;

class NotRegex extends Regex
{

    protected static $defaultMessageTemplate = 'This input should not match the regular expression {pattern}';

    function validate($value, $valueIdentifier = null)
    {
        parent::validate($value, $valueIdentifier);
        $this->success = ! $this->success;
        return $this->success;
    }
}