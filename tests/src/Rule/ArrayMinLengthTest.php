<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\Rule\ArrayMinLength as Rule;

class ArrayMinLengthTest extends \PHPUnit\Framework\TestCase
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
