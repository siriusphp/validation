<?php

namespace Latinosoft\Validation\Rule;

use Latinosoft\Validation\Rule\Integer as Rule;

class IntegerTest extends \PHPUnit\Framework\TestCase
{

    function setUp(): void
    {
        $this->rule = new Rule();
    }

    function testValidation()
    {
        $this->assertTrue($this->rule->validate('0'));
        $this->assertTrue($this->rule->validate('10'));
        $this->assertFalse($this->rule->validate('10.3'));
    }
}
