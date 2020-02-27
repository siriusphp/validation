<?php
declare(strict_types=1);
namespace Sirius\Validation\Rule;

class Url extends AbstractRule
{
    const MESSAGE = 'This input is not a valid URL';
    const LABELED_MESSAGE = '{label} is not a valid URL';

    public function validate($value, string $valueIdentifier = null):bool
    {
        $this->value   = $value;
        $this->success = (bool) filter_var($value, FILTER_VALIDATE_URL);

        return $this->success;
    }
}
