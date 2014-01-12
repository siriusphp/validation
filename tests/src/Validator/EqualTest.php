<?php

namespace Sirius\Validation\Validator;

use Sirius\Validation\Validator\Equal as Validator;
use Sirius\Validation\DataWrapper\ArrayWrapper;

class EqualTest extends \PHPUnit_Framework_TestCase  {
    
    function setUp() {
        $this->validator = new Validator();
    }
    
    function testValidationWithOptionSet() {
        $this->validator->setOption(Validator::OPTION_VALUE, '123');
        $this->assertTrue($this->validator->validate('123'));
        $this->assertFalse($this->validator->validate('abc'));
    }
    
    function testValidationWithoutOptionSet() {
        $this->assertTrue($this->validator->validate('abc'));
        $this->assertTrue($this->validator->validate(null));
    }
    
}