<?php

namespace Sirius\Validation\Rule;

class InList extends AbstractRule
{
    const OPTION_LIST = 'list';

    const MESSAGE = 'This input is not one of the accepted values';
    const LABELED_MESSAGE = '{label} is not one of the accepted values';

    protected $optionsIndexMap = array(
        0 => self::OPTION_LIST
    );

    public function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;
        if (! isset($this->options['list'])) {
            $this->success = true;
        } else {
            if (is_array($this->options['list'])) {
                $this->success = in_array($value, $this->options['list']);
            }
        }

        return $this->success;
    }
}
