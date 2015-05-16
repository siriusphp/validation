<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\Rule\ArrayMaxLength as Rule;

class ArrayMaxLengthTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->rule = new Rule();
    }

    function testValidationWithoutALimit()
    {
        $this->assertTrue($this->rule->validate(array()));
    }
}
