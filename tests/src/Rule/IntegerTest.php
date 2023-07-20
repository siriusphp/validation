<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\Rule\Integer as Rule;

final class IntegerTest extends \PHPUnit\Framework\TestCase
{

    protected function setUp(): void
    {
        $this->rule = new Rule();
    }

    function testValidation(): void
    {
        $this->assertTrue($this->rule->validate('0'));
        $this->assertTrue($this->rule->validate('10'));
        $this->assertFalse($this->rule->validate('10.3'));
    }
}
