<?php
declare(strict_types=1);
namespace Sirius\Validation\Rule;

class NotRegex extends Regex
{
    const MESSAGE = 'This input should not match the regular expression {pattern}';
    const LABELED_MESSAGE = '{label} Tshould not match the regular expression {pattern}';

    public function validate($value, string $valueIdentifier = null):bool
    {
        parent::validate($value, $valueIdentifier);
        $this->success = ! $this->success;

        return $this->success;
    }
}
