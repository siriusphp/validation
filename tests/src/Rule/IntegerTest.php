<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\Rule\Integer as Validator;

class IntegerTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->validator = new Validator();
    }

    function testValidation()
    {
        $this->assertTrue($this->validator->validate('10'));
        $this->assertFalse($this->validator->validate('10.3'));
    }
}
