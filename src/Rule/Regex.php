<?php
declare(strict_types=1);
namespace Sirius\Validation\Rule;

class Regex extends AbstractRule
{
    const OPTION_PATTERN = 'pattern';

    const MESSAGE = 'This input does not match the regular expression {pattern}';
    const LABELED_MESSAGE = '{label} does not match the regular expression {pattern}';

    protected $optionsIndexMap = [
        0 => self::OPTION_PATTERN
    ];

    public function validate($value, string $valueIdentifier = null):bool
    {
        $this->value = $value;
        if (isset($this->options['pattern'])) {
            $this->success = (bool) preg_match($this->options['pattern'], $value);
        } else {
            $this->success = true;
        }

        return $this->success;
    }
}
