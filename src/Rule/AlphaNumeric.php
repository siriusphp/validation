<?php
namespace Sirius\Validation\Rule;

class AlphaNumeric extends AbstractRule
{
    const MESSAGE = 'This input must contain only letters and digits';

    const LABELED_MESSAGE = '{label} must contain only letters and digits';

    public function validate($value, $valueIdentifier = null)
    {
        $this->value   = $value;
        $this->success = (bool) ctype_alnum((string) str_replace(' ', '', $value));

        return $this->success;
    }
}
