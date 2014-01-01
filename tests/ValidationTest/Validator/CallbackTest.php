<?php

namespace Sirius\Validation\Validator\Test;

use Sirius\Validation\Validator\Callback as Validator;

class CallbackTest extends \PHPUnit_Framework_TestCase  {
    
    function setUp() {
        $this->validator = new Validator();
    }
    
    function testValidationWithoutAValidCallback() {
        $this->validator->setOption(Validator::OPTION_CALLBACK, 'ssss');
        $this->assertTrue($this->validator->validate('abc'));
    }
    
    function testUniqueIdForClosures() {
        $this->validator->setOption(Validator::OPTION_CALLBACK, function($value, $valueIdentifier) {
        	return true;
        });
        $this->assertNotNull($this->validator->getUniqueId());
    }
}