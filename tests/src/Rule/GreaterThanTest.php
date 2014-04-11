<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\Rule\GreaterThan as Validator;

class GreaterThanTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->validator = new Validator();
    }

    function testExclusiveValidation()
    {
        $this->validator->setOption('inclusive', false);
        $this->validator->setOption('min', 100);
        $this->assertFalse($this->validator->validate(100));
    }

    function testValidationWithoutALimit()
    {
        $this->assertTrue($this->validator->validate(0));
    }
}
