<?php

namespace Latinosoft\Validation\Rule;

use Latinosoft\Validation\Rule\ArrayMaxLength as Rule;

class ArrayMaxLengthTest extends \PHPUnit\Framework\TestCase
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
