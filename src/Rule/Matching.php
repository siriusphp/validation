<?php
declare(strict_types=1);
namespace Sirius\Validation\Rule;

class Matching extends AbstractRule
{
    const OPTION_ITEM = 'item';

    const MESSAGE = 'This input does not match {item}';
    const LABELED_MESSAGE = '{label} does not match {item}';

    protected $optionsIndexMap = [
        0 => self::OPTION_ITEM
    ];

    public function validate($value, string $valueIdentifier = null):bool
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
