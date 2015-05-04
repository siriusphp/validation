<?php
namespace Sirius\Validation\Rule;

class ArrayMinLength extends AbstractRule
{

    const OPTION_MIN = 'min';

    const MESSAGE = 'This input should contain at least {min} items';
    const LABELED_MESSAGE = '{label} should contain at least {min} items';

    protected $options = array();

    public function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;
        if (!isset($this->options['min'])) {
            $this->success = true;
        } else {
            $this->success = is_array($value) && count($value) >= $this->options['min'];
        }

        return $this->success;
    }
}
