<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\Rule\ArrayMinLength as Validator;

class ArrayMinLengthTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->validator = new Validator();
    }

    function testValidationWithoutALimit()
    {
        $this->assertTrue($this->validator->validate(array()));
    }
}
