<?php
namespace Latinosoft\Validation\Rule;

class Url extends AbstractRule
{
    const MESSAGE = 'This input is not a valid URL';
    const LABELED_MESSAGE = '{label} is not a valid URL';

    public function validate($value, $valueIdentifier = null)
    {
        $this->value   = $value;
        $this->success = (bool) filter_var($value, FILTER_VALIDATE_URL);

        return $this->success;
    }
}
