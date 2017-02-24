<?php
namespace Sirius\Validation\Rule;

class MinLength extends AbstractStringRule
{
    const OPTION_MIN = 'min';
    const OPTION_ENCODING = 'encoding';

    const MESSAGE = 'This input should have at least {min} characters';
    const LABELED_MESSAGE = '{label} should have at least {min} characters';

    protected $options = array();

    protected $optionsIndexMap = array(
        0 => self::OPTION_MIN,
        1 => self::OPTION_ENCODING
    );

    public function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;
        if (! isset($this->options['min'])) {
            $this->success = true;
        } else {
            $this->success = $this->getStringLength($value) >= $this->options['min'];
        }

        return $this->success;
    }
}
