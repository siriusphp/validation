<?php
namespace Sirius\Validation\Rule;

class Between extends AbstractValidator
{

    const OPTION_MIN = 'min';
    const OPTION_MAX = 'max';

    protected static $defaultMessageTemplate = 'This input must be between {min} and {max}';

    protected $options = array();

    public function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;
        $minValidator = new LessThan();
        if (isset($this->options['max'])) {
            $minValidator->setOption('max', $this->options['max']);
        }
        $maxValidator = new GreaterThan();
        if (isset($this->options['min'])) {
            $maxValidator->setOption('min', $this->options['min']);
        }
        $this->success = $minValidator->validate($value, $valueIdentifier) && $maxValidator->validate(
                $value,
                $valueIdentifier
            );
        return $this->success;
    }
}
