<?php
declare(strict_types=1);
namespace Sirius\Validation\Rule;

class IpAddress extends AbstractRule
{
    const MESSAGE = 'This input is not a valid IP address';
    const LABELED_MESSAGE = '{label} is not a valid IP address';

    public function validate($value, string $valueIdentifier = null):bool
    {
        $this->value = $value;
        // Do not allow private and reserved range IPs
        $flags = FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE;
        if (strpos($value, ':') !== false) {
            $this->success = (bool) filter_var($value, FILTER_VALIDATE_IP, $flags | FILTER_FLAG_IPV6);
        } else {
            $this->success = (bool) filter_var($value, FILTER_VALIDATE_IP, $flags | FILTER_FLAG_IPV4);
        }

        return $this->success;
    }
}
