<?php

namespace Sirius\Validation\Validator;

use Sirius\Validation\Validator\ArrayMinLength as Validator;

class ArrayMinLengthTest extends \PHPUnit_Framework_TestCase  {
    
    function setUp() {
        $this->validator = new Validator();
    }
    
    function testValidationWithoutALimit() {
        $this->assertTrue($this->validator->validate(array()));
    }
}