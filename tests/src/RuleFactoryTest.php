<?php

namespace Sirius\Validation;

class TestingCustomValidator extends Rule\AbstractValidator
{

    function validate($value, $valueIdentifier = null)
    {
        return (bool)($value % 2);
    }
}

class RuleFactoryTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->ruleFactory = new RuleFactory();
    }

    function testRegistrationOfValidatorClasses()
    {
        $this->ruleFactory->register('odd', '\Sirius\Validation\TestingCustomValidator');

        $validator = $this->ruleFactory ->createValidator('odd');
        $this->assertTrue($validator instanceof TestingCustomValidator);
        $this->assertTrue($validator->validate(3));
        $this->assertFalse($validator->validate(4));
    }
}
