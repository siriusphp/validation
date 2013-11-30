<?php
namespace Sirius\Validation\Validator;

class Website extends AbstractValidator
{

    const WEBSITE_REGEX = '@^(http|https)\://[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(:[a-zA-Z0-9]*)?/?([a-zA-Z0-9\-\._\?\,\'/\\\+&amp;%\$#\=~!])*$@';

    protected static $defaultMessageTemplate = 'This input must be a valid website address';

    function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;
        $this->success = (bool) preg_match(static::WEBSITE_REGEX, $value);
        return $this->success;
    }
}