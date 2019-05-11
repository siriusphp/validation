<?php

namespace Latinosoft\Validation\Rule;

use Latinosoft\Validation\Rule\InList as Rule;

class InListTest extends \PHPUnit\Framework\TestCase
{

    function setUp(): void
    {
        $this->rule = new Rule();
    }

    function testValidationWithoutALIstOfAcceptableValues()
    {
        $this->assertTrue($this->rule->validate('abc'));
    }
}
