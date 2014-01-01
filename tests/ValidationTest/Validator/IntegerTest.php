<?php

namespace Sirius\Validation\Validator\Test;

use Sirius\Validation\Validator\Integer as Validator;

class IntegerTest extends \PHPUnit_Framework_TestCase  {
    
    function setUp() {
        $this->validator = new Validator();
    }
    
    function testValidation() {
        $this->assertTrue($this->validator->validate('10'));
        $this->assertFalse($this->validator->validate('10.3'));
    }
}