<?php

namespace Sirius\Validation\Rule;

class InList extends AbstractValidator
{

    protected static $defaultMessageTemplate = 'This input is not one of the accepted values';

    public function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;
        if (!isset($this->options['list'])) {
            $this->success = true;
        } else {
            if (is_array($this->options['list'])) {
                $this->success = in_array($value, $this->options['list']);
            }
        }
        return $this->success;
    }
}
