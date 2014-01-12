<?php

namespace Sirius\Validation\Validator;

use Sirius\Validation\Validator\ArrayMaxLength as Validator;

class ArrayMaxLengthTest extends \PHPUnit_Framework_TestCase  {
    
    function setUp() {
        $this->validator = new Validator();
    }
    
    function testValidationWithoutALimit() {
        $this->assertTrue($this->validator->validate(array()));
    }
}