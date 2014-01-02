<?php
namespace Sirius\Validation\Validator;

class RequiredWith extends AbstractValidator
{
    const OPTION_ITEM = 'item';
    
    protected static $defaultMessageTemplate = 'This field is required';

    function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;
        if (isset($this->options[self::OPTION_ITEM]) && $this->context->getItemValue($this->options[self::OPTION_ITEM]) === null) {
            $this->success = true;
        } else {
            $this->success = ($value !== null || trim($value) !== '');
        }
        return $this->success;
    }
}