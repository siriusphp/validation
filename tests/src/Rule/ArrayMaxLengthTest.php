<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\Rule\ArrayMaxLength as Rule;

class ArrayMaxLengthTest extends \PHPUnit\Framework\TestCase
{

    protected function setUp(): void
    {
        $this->rule = new Rule();
    }

    function testValidationWithoutALimit()
    {
        $this->assertTrue($this->rule->validate(array()));
    }
}
