<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\Rule\ArrayMinLength as Rule;

final class ArrayMinLengthTest extends \PHPUnit\Framework\TestCase
{

    protected function setUp(): void
    {
        $this->rule = new Rule();
    }

    function testValidationWithoutALimit(): void
    {
        $this->assertTrue($this->rule->validate(array()));
    }
}
