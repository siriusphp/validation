<?php
namespace Sirius\Validation\Rule;

class MaxLength extends AbstractRule
{

    const OPTION_MAX = 'max';

    const MESSAGE = 'This input should have less than {max} characters';
    const LABELED_MESSAGE = '{label} should have less than {max} characters';

    protected $options = array();

    protected $optionsIndexMap = array(
        0 => self::OPTION_MAX
    );

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
