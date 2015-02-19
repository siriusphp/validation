<?php
namespace Sirius\Validation\Rule;

class ArrayMaxLength extends AbstractValidator
{

    const OPTION_MAX = 'max';

    protected static $defaultMessageTemplate = 'This input should contain less than {min} items';

    protected $options = array();

    public function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;
        if (!isset($this->options['max'])) {
            $this->success = true;
        } else {
            $this->success = is_array($value) && count($value) <= $this->options['max'];
        }
        return $this->success;
    }
}
