<?php

namespace Latinosoft\Validation\Rule;

use Latinosoft\Validation\Rule\Number as Rule;

class NumberTest extends \PHPUnit\Framework\TestCase
{

    function setUp(): void
    {
        $this->rule = new Rule();
    }

    function testValidation()
    {
        $this->assertTrue($this->rule->validate('0'));
        $this->assertTrue($this->rule->validate('0.3'));
        $this->assertFalse($this->rule->validate('0,3'));
    }
}
