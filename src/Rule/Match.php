<?php
namespace Sirius\Validation\Rule;

class Match extends AbstractValidator
{

    const OPTION_ITEM = 'item';

    protected static $defaultMessageTemplate = 'This input does not match {item}';

    public function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;
        if (isset($this->options[self::OPTION_ITEM])) {
            $this->success = ($value == $this->context->getItemValue($this->options[self::OPTION_ITEM]));
        } else {
            $this->success = true;
        }
        return $this->success;
    }
}
