<?php
namespace Sirius\Validation\Validator;

class Email extends AbstractValidator
{

    const EMAIL_REGEX = '/^[-_a-z0-9\'+*$^&%=~!?{}]++(?:\.[-_a-z0-9\'+*$^&%=~!?{}]+)*+@(?:(?![-.])[-a-z0-9.]+(?<![-.])\.[a-z]{2,6}|\d{1,3}(?:\.\d{1,3}){3})(?::\d++)?$/iD';

    protected static $defaultMessageTemplate = 'This input must be a valid email address';

    function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;
        $this->success = (bool) preg_match(static::EMAIL_REGEX, $value);
        return $this->success;
    }
}