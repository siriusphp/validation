<?php
declare(strict_types=1);

namespace Sirius\Validation\Rule;

class LessThan extends AbstractRule
{
    const OPTION_MAX = 'max';
    const OPTION_INCLUSIVE = 'inclusive';

    const MESSAGE = 'This input should be less than {max}';
    const LABELED_MESSAGE = '{label} should be less than {max}';

    protected array $options = [
        'inclusive' => true
    ];

    protected array $optionsIndexMap = [
        0 => self::OPTION_MAX,
        1 => self::OPTION_INCLUSIVE
    ];

    public function validate(mixed $value, string $valueIdentifier = null): bool
    {
        $this->value = $value;
        if (!isset($this->options['max'])) {
            $this->success = true;
        } else {
            if ($this->options['inclusive']) {
                $this->success = $value <= $this->options['max'];
            } else {
                $this->success = $value < $this->options['max'];
            }
        }

        return $this->success;
    }
}
