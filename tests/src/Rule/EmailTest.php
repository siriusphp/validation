<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\Rule\Email as Validator;

class EmailTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->validator = new Validator();
    }

    function testValidation()
    {
        $this->assertFalse($this->validator->validate(''));
        $this->assertTrue($this->validator->validate('me@domain.com'));
    }

}
