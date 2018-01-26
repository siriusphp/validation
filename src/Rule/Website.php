<?php
namespace Sirius\Validation\Rule;

class Website extends AbstractRule
{
    const WEBSITE_REGEX = '@^((http|https)\:)//.+$@i';

    const MESSAGE = 'This input must be a valid website address';
    const LABELED_MESSAGE = '{label} must be a valid website address';

    public function validate($value, $valueIdentifier = null)
    {
        $this->value   = $value;
        $this->success = (substr($value, 0, 2) == '//')
                        || (preg_match(static::WEBSITE_REGEX, $value) && filter_var(
                            $value,
                            FILTER_VALIDATE_URL,
                            FILTER_FLAG_HOST_REQUIRED
                        ));

        return $this->success;
    }
}
