<?php

namespace Sirius\Validation\Validator\Test;

use Sirius\Validation\Validator\Number as Validator;

class NumberTest extends \PHPUnit_Framework_TestCase  {
    
    function setUp() {
        $this->validator = new Validator();
    }
    
    function testValidation() {
        $this->assertTrue($this->validator->validate('0.3'));
        $this->assertFalse($this->validator->validate('0,3'));
    }
}