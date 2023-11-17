<?php
declare(strict_types=1);

namespace Sirius\Validation\Rule;

abstract class AbstractStringRule extends AbstractRule
{
    protected function getStringLength(string $str): int
    {
        if (function_exists('mb_strlen')) {
            return mb_strlen(
                $str,
                $this->options['encoding'] ?? mb_internal_encoding()
            );
        }

        return strlen($str);
    }
}
