<?php
declare(strict_types=1);

namespace Sirius\Validation\Rule;

class Between extends AbstractRule
{
    const OPTION_MIN = 'min';
    const OPTION_MAX = 'max';

    const MESSAGE = 'This input must be between {min} and {max}';
    const LABELED_MESSAGE = '{label} must be between {min} and {max}';

    protected array $optionsIndexMap = [
        0 => self::OPTION_MIN,
        1 => self::OPTION_MAX
    ];

    public function validate(mixed $value, string $valueIdentifier = null): bool
    {
        $this->value = $value;
        $minValidator = new LessThan();
        if (isset($this->options['max'])) {
            $minValidator->setOption('max', $this->options['max']);
        }
        $maxValidator = new GreaterThan();
        if (isset($this->options['min'])) {
            $maxValidator->setOption('min', $this->options['min']);
        }
        $this->success = $minValidator->validate($value, $valueIdentifier) && $maxValidator->validate(
                $value,
                $valueIdentifier
            );

        return $this->success;
    }
}
