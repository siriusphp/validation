<?php
declare(strict_types=1);

namespace Sirius\Validation\Rule;

class RequiredWith extends Required
{
    const OPTION_ITEM = 'item';

    const MESSAGE = 'This field is required';
    const LABELED_MESSAGE = '{label} is required';

    protected array $optionsIndexMap = [
        0 => self::OPTION_ITEM
    ];

    public function validate(mixed $value, string $valueIdentifier = null): bool
    {
        $this->value = $value;

        $relatedItemPath = $this->getRelatedValueIdentifier((string) $valueIdentifier, $this->options[self::OPTION_ITEM]);
        $relatedItemValue = $this->context && $relatedItemPath !== null ? $this->context->getItemValue($relatedItemPath) : null;

        if (isset($this->options[self::OPTION_ITEM]) && $relatedItemValue !== null) {
            $this->success = ($value !== null && (!is_string($value) || trim($value) !== ''));
        } else {
            $this->success = true;
        }

        return $this->success;
    }
}
