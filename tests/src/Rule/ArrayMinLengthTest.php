<?php

namespace Latinosoft\Validation\Rule;

use Latinosoft\Validation\Rule\ArrayMinLength as Rule;

class ArrayMinLengthTest extends \PHPUnit\Framework\TestCase
{

    function setUp(): void
    {
        $this->rule = new Rule();
    }

    function testValidationWithoutALimit()
    {
        $this->assertTrue($this->rule->validate(array()));
    }
}
