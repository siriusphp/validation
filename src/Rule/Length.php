<?php
declare(strict_types=1);
namespace Sirius\Validation\Rule;

class Length extends AbstractRule
{
    const OPTION_MIN = 'min';
    const OPTION_MAX = 'max';
    const OPTION_ENCODING = 'encoding';

    const MESSAGE = 'This input must be between {min} and {max} characters long';
    const LABELED_MESSAGE = '{label} must be between {min} and {max} characters long';

    protected $options = [];

    protected $optionsIndexMap = [
        0 => self::OPTION_MIN,
        1 => self::OPTION_MAX,
        2 => self::OPTION_ENCODING
    ];

    public function validate($value, string $valueIdentifier = null):bool
    {
        $this->value  = $value;
        $maxValidator = new MaxLength();
        if (isset($this->options['max'])) {
            $maxValidator->setOption('max', $this->options['max']);
        }
        $minValidator = new MinLength();
        if (isset($this->options['min'])) {
            $minValidator->setOption('min', $this->options['min']);
        }
        $this->success = $minValidator->validate($value, $valueIdentifier) && $maxValidator->validate(
            $value,
            $valueIdentifier
        );

        return $this->success;
    }
}
