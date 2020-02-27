<?php
declare(strict_types=1);
namespace Sirius\Validation\Rule;

class ArrayMaxLength extends AbstractRule
{
    const OPTION_MAX = 'max';

    const MESSAGE = 'This input should contain less than {min} items';

    const LABELED_MESSAGE = '{label} should contain less than {min} items';

    protected $options = [];

    protected $optionsIndexMap = [
        self::OPTION_MAX
    ];

    public function validate($value, string $valueIdentifier = null):bool
    {
        $this->value = $value;
        if (! isset($this->options['max'])) {
            $this->success = true;
        } else {
            $this->success = is_array($value) && count($value) <= $this->options['max'];
        }

        return $this->success;
    }
}
