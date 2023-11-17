<?php
declare(strict_types=1);

namespace Sirius\Validation\Rule;

class AlphaNumHyphen extends AbstractRule
{
    const MESSAGE = 'This input must contain only letters, digits, spaces, hyphens and underscores';
    const LABELED_MESSAGE = '{label} must contain only letters, digits, spaces, hyphens and underscores';

    public function validate(mixed $value, string $valueIdentifier = null): bool
    {
        $this->value = $value;
        $this->success = (bool)ctype_alnum(
            str_replace(
                [
                    ' ',
                    '_',
                    '-'
                ],
                '',
                (string) $value
            )
        );

        return $this->success;
    }
}
