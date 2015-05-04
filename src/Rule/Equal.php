<?php
namespace Sirius\Validation\Rule;

class Equal extends AbstractRule
{

    const OPTION_VALUE = 'value';

    protected static $defaultMessageTemplate = 'This input is not equal to {value}';
    const MESSAGE = 'This input is not equal to {value}';
    const LABELED_MESSAGE = '{label} is not equal to {value}';

    public function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;
        if (isset($this->options[self::OPTION_VALUE])) {
            $this->success = ($value == $this->options[self::OPTION_VALUE]);
        } else {
            $this->success = true;
        }

        return $this->success;
    }
}
