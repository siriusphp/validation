<?php
declare(strict_types=1);
namespace Sirius\Validation\Rule;

class EmailDomain extends AbstractRule
{
    const MESSAGE = 'This the email address does not belong to a valid domain';
    const LABELED_MESSAGE = '{label} does not belong to a valid domain';

    public function validate($value, string $valueIdentifier = null):bool
    {
        $value       = (string) $value;
        $this->value = $value;
        // Check if the email domain has a valid MX record
        $this->success = (bool) checkdnsrr(preg_replace('/^[^@]+@/', '', $value), 'MX');

        return $this->success;
    }
}
