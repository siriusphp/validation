<?php

namespace Sirius\Validation;

use Sirius\Validation\Rule\AbstractRule;

class TestingCustomRule extends AbstractRule
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
        $this->ruleFactory->register('even', '\Sirius\Validation\TestingCustomRule');

        $validator = $this->ruleFactory ->createValidator('even');
        $this->assertTrue($validator instanceof TestingCustomRule);
        $this->assertTrue($validator->validate(3));
        $this->assertFalse($validator->validate(4));
        $this->assertEquals('Value is not valid', (string)$validator->getMessage());
    }
    
    function testCustomErrorMessages() {
        $this->ruleFactory->register('even', '\Sirius\Validation\TestingCustomRule', 'This should be even', '{label} should be even');
        
        $validatorWithLabel = $this->ruleFactory ->createValidator('even', null, null, 'Number');
        $validatorWithLabel->validate(4);
        $this->assertEquals('Number should be even', (string)$validatorWithLabel->getMessage());
        
        $validator = $validator = $this->ruleFactory ->createValidator('even');
        $validator->validate(4);
        $this->assertEquals('This should be even', (string)$validator->getMessage());
        
    }
}
