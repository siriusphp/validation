<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\Rule\Between as Rule;

final class BetweenTest extends \PHPUnit\Framework\TestCase
{

    protected function setUp(): void
    {
        $this->rule = new Rule();
    }

    function testValidation(): void
    {
        $this->rule->setOption('min', 50);
        $this->rule->setOption('max', 100);
        $this->assertFalse($this->rule->validate(40));
        $this->assertFalse($this->rule->validate(110));
        $this->assertTrue($this->rule->validate(80));
    }

}
