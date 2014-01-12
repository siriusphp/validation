<?php

namespace Sirius\Validation;

class TestingCustomValidator extends Validator\AbstractValidator {
    
    function validate($value, $valueIdentifier = null) {
        return (bool)($value % 2);
    }
}

class ValidatorFactorTest extends \PHPUnit_Framework_TestCase {
    
    function setUp() {
        $this->validatorFactory = new ValidatorFactory();
    }
    
    function testRegistrationOfValidatorClasses() {
        $this->validatorFactory->register('odd', '\Sirius\Validation\TestingCustomValidator');

        $validator = $this->validatorFactory->createValidator('odd');
        $this->assertTrue($validator instanceof TestingCustomValidator);
        $this->assertTrue($validator->validate(3));
        $this->assertFalse($validator->validate(4));
    }
}