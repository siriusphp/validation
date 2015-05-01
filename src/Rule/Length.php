<?php
namespace Sirius\Validation\Rule;

class Length extends AbstractRule
{

    const OPTION_MIN = 'min';
    const OPTION_MAX = 'max';

    const MESSAGE = 'This input must be between {min} and {max} characters long';
    const LABELED_MESSAGE = '{label} must be between {min} and {max} characters long';
    
    protected $options = array();

    public function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;
        $maxValidator = new MinLength();
        if (isset($this->options['max'])) {
            $maxValidator->setOption('max', $this->options['max']);
        }
        $minValidator = new MaxLength();
        if (isset($this->options['min'])) {
            $minValidator->setOption('min', $this->options['min']);
        }
        $this->success = $minValidator->validate($value, $valueIdentifier) && $maxValidator->validate(
                $value,
                $valueIdentifier
            );
        return $this->success;
    }
}
