<?php

namespace Sirius\Validation\Validator\Test;

use Sirius\Validation\Validator\NotInList as Validator;

class NotInListTest extends \PHPUnit_Framework_TestCase  {
    
    function setUp() {
        $this->validator = new Validator();
    }
    
    function testValidationWithoutAListOfForbiddenValues() {
        $this->assertTrue($this->validator->validate('abc'));
    }
}