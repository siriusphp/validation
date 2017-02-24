<?php
namespace Sirius\Validation\Rule;

class NotRegex extends Regex
{
    const MESSAGE = 'This input should not match the regular expression {pattern}';
    const LABELED_MESSAGE = '{label} Tshould not match the regular expression {pattern}';

    public function validate($value, $valueIdentifier = null)
    {
        parent::validate($value, $valueIdentifier);
        $this->success = ! $this->success;

        return $this->success;
    }
}
