<?php
namespace Sirius\Validation\Rule;

class Alpha extends AbstractRule
{
    const MESSAGE = 'This input can contain only letters';
    const LABELED_MESSAGE = '{label} can contain only letters';

    public function validate($value, $valueIdentifier = null)
    {
        $this->value   = $value;
        $this->success = (bool) ctype_alpha((string) str_replace(' ', '', $value));

        return $this->success;
    }
}
