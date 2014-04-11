<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\Rule\Equal as Validator;

class EqualTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->validator = new Validator();
    }

    function testValidationWithOptionSet()
    {
        $this->validator->setOption(Validator::OPTION_VALUE, '123');
        $this->assertTrue($this->validator->validate('123'));
        $this->assertFalse($this->validator->validate('abc'));
    }

    function testValidationWithoutOptionSet()
    {
        $this->assertTrue($this->validator->validate('abc'));
        $this->assertTrue($this->validator->validate(null));
    }

}
