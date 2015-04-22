<?php
namespace Sirius\Validation\Rule;

class MaxLength extends AbstractValidator
{

    const OPTION_MAX = 'max';

    const MESSAGE = 'This input should have less than {max} characters';
    const LABELED_MESSAGE = '{label} should have less than {max} characters';
    
    protected $options = array();

    public function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;
        if (!isset($this->options['max'])) {
            $this->success = true;
        } else {
            $this->success = strlen($value) <= $this->options['max'];
        }
        return $this->success;
    }
}
