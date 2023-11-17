<?php
declare(strict_types=1);

namespace Sirius\Validation\Rule;

class Equal extends AbstractRule
{
    const OPTION_VALUE = 'value';

    const MESSAGE = 'This input is not equal to {value}';
    const LABELED_MESSAGE = '{label} is not equal to {value}';

    protected array $optionsIndexMap = [
        0 => self::OPTION_VALUE
    ];

    public function validate(mixed $value, string $valueIdentifier = null): bool
    {
        $this->value = $value;
        if (isset($this->options[self::OPTION_VALUE])) {
            $this->success = ($value == $this->options[self::OPTION_VALUE]);
        } else {
            $this->success = true;
        }

        return $this->success;
    }
}
