<?php
namespace Sirius\Validation\Rule;

class Match extends AbstractRule
{
    const OPTION_ITEM = 'item';

    const MESSAGE = 'This input does not match {item}';
    const LABELED_MESSAGE = '{label} does not match {item}';

    protected $optionsIndexMap = array(
        0 => self::OPTION_ITEM
    );

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
