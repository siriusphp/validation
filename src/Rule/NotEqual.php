<?php
declare(strict_types=1);
namespace Sirius\Validation\Rule;

class NotEqual extends Equal
{
    const MESSAGE = 'This input is equal to {value}';
    const LABELED_MESSAGE = '{label} is equal to {value}';

    public function validate($value, string $valueIdentifier = null):bool
    {
        parent::validate($value, $valueIdentifier);
        $this->success = ! $this->success;

        return $this->success;
    }
}
