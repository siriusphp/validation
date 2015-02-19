<?php
namespace Sirius\Validation\Rule;

class ArrayLength extends AbstractValidator
{

    const OPTION_MIN = 'min';
    const OPTION_MAX = 'max';

    protected static $defaultMessageTemplate = 'This input contain between {min} and {max} items';

    protected $options = array();

    public function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;
        $maxValidator = new ArrayMaxLength();
        if (isset($this->options['max'])) {
            $maxValidator->setOption('max', $this->options['max']);
        }
        $minValidator = new ArrayMinLength();
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
