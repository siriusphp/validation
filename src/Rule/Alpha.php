<?php
declare(strict_types=1);
namespace Sirius\Validation\Rule;

class Alpha extends AbstractRule
{
    const MESSAGE = 'This input can contain only letters';
    const LABELED_MESSAGE = '{label} can contain only letters';

    public function validate($value, string $valueIdentifier = null):bool
    {
        $this->value   = $value;
        $this->success = (bool) ctype_alpha((string) str_replace(' ', '', $value));

        return $this->success;
    }
}
