<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\Rule\ArrayMaxLength as Rule;

final class ArrayMaxLengthTest extends \PHPUnit\Framework\TestCase
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
