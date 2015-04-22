<?php
namespace Sirius\Validation\Rule;

class RequiredWithout extends Required
{
    const OPTION_ITEM = 'item';

    const MESSAGE = 'This field is required';
    const LABELED_MESSAGE = '{label} is required';
    
    public function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;
        if (isset($this->options[self::OPTION_ITEM]) && $this->context->getItemValue(
                $this->options[self::OPTION_ITEM]
            ) === null
        ) {
            $this->success = ($value !== null || trim($value) !== '');
        } else {
            $this->success = true;
        }
        return $this->success;
    }
}
