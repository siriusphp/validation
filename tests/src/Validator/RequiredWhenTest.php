<?php

namespace Sirius\Validation\Validator;

use Sirius\Validation\Validator\RequiredWhen as Validator;
use Sirius\Validation\DataWrapper\ArrayWrapper;

class RequiredWhenTest extends \PHPUnit_Framework_TestCase  {
    
    function setUp() {
        $this->validator = new Validator();
    }
    
    function testValidationWithItemValid() {
        $this->validator->setOption(Validator::OPTION_ITEM, 'email');
        $this->validator->setOption(Validator::OPTION_RULE, 'Email');
        $this->validator->setContext(new ArrayWrapper(array(
        	'email' => 'me@domain.com'
        )));
        $this->assertTrue($this->validator->validate('abc'));
        $this->assertFalse($this->validator->validate(null));
    }
    
    function testValidationWithItemNotValid() {
        $this->validator->setOption(Validator::OPTION_ITEM, 'email');
        $this->validator->setOption(Validator::OPTION_RULE, 'Sirius\Validation\Validator\Email');
        $this->validator->setContext(new ArrayWrapper(array(
        	'email' => 'not_a_valid_email'
        )));
        $this->assertTrue($this->validator->validate('abc'));
        $this->assertTrue($this->validator->validate(null));
    }
    
    function testValidationWithoutItem() {
        $this->validator->setOption(Validator::OPTION_RULE, 'Sirius\Validation\Validator\Email');
        $this->validator->setContext(new ArrayWrapper(array(
        	'email' => 'not_a_valid_email'
        )));
        $this->assertTrue($this->validator->validate('abc'));
        $this->assertTrue($this->validator->validate(null));
    }
    
    function testItemRuleSetAsValidatorObject() {
        $this->validator->setOption(Validator::OPTION_ITEM, 'email');
        $this->validator->setOption(Validator::OPTION_RULE, new \Sirius\Validation\Validator\Email);
        $this->validator->setContext(new ArrayWrapper(array(
        	'email' => 'me@domain.com'
        )));
        $this->assertTrue($this->validator->validate('abc'));
        $this->assertFalse($this->validator->validate(null));
    }

    function testExceptionThrownOnInvalidItemRule() {
        $this->setExpectedException('\InvalidArgumentException');
        $this->validator->setOption(Validator::OPTION_ITEM, 'email');
        $this->validator->setOption(Validator::OPTION_RULE, new \stdClass());
        $this->validator->setContext(new ArrayWrapper(array(
        	'email' => 'me@domain.com'
        )));
        $this->assertTrue($this->validator->validate('abc'));
    }
}