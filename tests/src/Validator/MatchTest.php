<?php

namespace Sirius\Validation\Validator;

use Sirius\Validation\Validator\Match as Validator;
use Sirius\Validation\DataWrapper\ArrayWrapper;

class MatchTest extends \PHPUnit_Framework_TestCase  {
    
    function setUp() {
        $this->validator = new Validator();
        $this->validator->setContext(new ArrayWrapper(array(
        	'password' => 'secret'
        )));
    }
    
    function testValidationWithItemPresent() {
        $this->validator->setOption(Validator::OPTION_ITEM, 'password');
        $this->assertTrue($this->validator->validate('secret'));
        $this->assertFalse($this->validator->validate('abc'));
    }
    
    function testValidationWithoutItemPresent() {
        $this->assertTrue($this->validator->validate('abc'));
        $this->assertTrue($this->validator->validate(null));
    }
    
}