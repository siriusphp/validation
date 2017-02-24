<?php
namespace Sirius\Validation\Rule;

class AlphaNumHyphen extends AbstractRule
{
    const MESSAGE = 'This input must contain only letters, digits, spaces, hyphens and underscores';
    const LABELED_MESSAGE = '{label} must contain only letters, digits, spaces, hyphens and underscores';

    public function validate($value, $valueIdentifier = null)
    {
        $this->value   = $value;
        $this->success = (bool) ctype_alnum(
            (string) str_replace(
                array(
                    ' ',
                    '_',
                    '-'
                ),
                '',
                $value
            )
        );

        return $this->success;
    }
}
