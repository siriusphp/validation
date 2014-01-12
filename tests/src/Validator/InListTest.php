<?php

namespace Sirius\Validation\Validator;

use Sirius\Validation\Validator\InList as Validator;

class InListTest extends \PHPUnit_Framework_TestCase  {
    
    function setUp() {
        $this->validator = new Validator();
    }
    
    function testValidationWithoutALIstOfAcceptableValues() {
        $this->assertTrue($this->validator->validate('abc'));
    }
}