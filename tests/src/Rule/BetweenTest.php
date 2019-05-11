<?php

namespace Latinosoft\Validation\Rule;

use Latinosoft\Validation\Rule\Between as Rule;

class BetweenTest extends \PHPUnit\Framework\TestCase
{

    function setUp(): void
    {
        $this->rule = new Rule();
    }

    function testValidation()
    {
        $this->rule->setOption('min', 50);
        $this->rule->setOption('max', 100);
        $this->assertFalse($this->rule->validate(40));
        $this->assertFalse($this->rule->validate(110));
        $this->assertTrue($this->rule->validate(80));
    }

}
