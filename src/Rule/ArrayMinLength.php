<?php
declare(strict_types=1);

namespace Sirius\Validation\Rule;

class ArrayMinLength extends AbstractRule
{
    const OPTION_MIN = 'min';

    const MESSAGE = 'This input should contain at least {min} items';
    const LABELED_MESSAGE = '{label} should contain at least {min} items';

    protected array $optionsIndexMap = [
        0 => self::OPTION_MIN
    ];

    public function validate(mixed $value, string $valueIdentifier = null): bool
    {
        $this->value = $value;
        if (!isset($this->options['min'])) {
            $this->success = true;
        } else {
            $this->success = is_array($value) && count($value) >= $this->options['min'];
        }

        return $this->success;
    }
}
