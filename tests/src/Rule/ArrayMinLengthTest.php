<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\Rule\ArrayMinLength as Rule;

class ArrayMinLengthTest extends \PHPUnit_Framework_TestCase
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
