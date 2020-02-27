<?php
declare(strict_types=1);
namespace Sirius\Validation\Rule;

class MaxLength extends AbstractStringRule
{
    const OPTION_MAX = 'max';
    const OPTION_ENCODING = 'encoding';

    const MESSAGE = 'This input should have less than {max} characters';
    const LABELED_MESSAGE = '{label} should have less than {max} characters';

    protected $options = [];

    protected $optionsIndexMap = [
        0 => self::OPTION_MAX,
        1 => self::OPTION_ENCODING
    ];

    public function validate($value, string $valueIdentifier = null):bool
    {
        $this->value = $value;
        if (! isset($this->options['max'])) {
            $this->success = true;
        } else {
            $this->success = $this->getStringLength($value) <= $this->options['max'];
        }

        return $this->success;
    }
}
