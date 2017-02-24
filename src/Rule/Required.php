<?php
namespace Sirius\Validation\Rule;

class Required extends AbstractRule
{
    const MESSAGE = 'This field is required';
    const LABELED_MESSAGE = '{label} is required';

    public function validate($value, $valueIdentifier = null)
    {
        $this->value   = $value;
        $this->success = ($value !== null && $value !== '');

        return $this->success;
    }
}
