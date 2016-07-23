<?php
namespace Sirius\Validation\Rule;

class RequiredWithout extends Required
{
    const OPTION_ITEM = 'item';

    const MESSAGE = 'This field is required';
    const LABELED_MESSAGE = '{label} is required';

    protected $optionsIndexMap = array(
        0 => self::OPTION_ITEM
    );

    public function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;

        $relatedItemPath  = $this->getRelatedValueIdentifier($valueIdentifier, $this->options[self::OPTION_ITEM]);
        $relatedItemValue = $relatedItemPath !== null ? $this->context->getItemValue($relatedItemPath) : null;

        if (isset($this->options[self::OPTION_ITEM]) && $relatedItemValue === null) {
            $this->success = ($value !== null && trim($value) !== '');
        } else {
            $this->success = true;
        }

        return $this->success;
    }
}
