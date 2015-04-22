<?php
namespace Sirius\Validation\Rule;

class MinLength extends AbstractValidator
{

    const OPTION_MIN = 'min';

    const MESSAGE = 'This input should have at least {min} characters';
    const LABELED_MESSAGE = '{label} should have at least {min} characters';
    
    protected $options = array();

    public function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;
        if (!isset($this->options['min'])) {
            $this->success = true;
        } else {
            $this->success = strlen($value) >= $this->options['min'];
        }
        return $this->success;
    }
}
