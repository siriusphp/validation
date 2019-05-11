<?php

namespace Latinosoft\Validation;

use PHPUnit\Framework\TestCase;
use Latinosoft\Validation\Rule\AbstractRule;

class TestingCustomRule extends AbstractRule
{

    function validate($value, $valueIdentifier = null)
    {
        return (bool) ($value % 2);
    }
}

class RuleFactoryTest extends TestCase
{

    function setUp(): void
    {
        $this->ruleFactory = new RuleFactory();
    }

    function testRegistrationOfValidatorClasses()
    {
        $this->ruleFactory->register('even', '\Latinosoft\Validation\TestingCustomRule');

        $validator = $this->ruleFactory->createRule('even');
        $this->assertTrue($validator instanceof TestingCustomRule);
        $this->assertTrue($validator->validate(3));
        $this->assertFalse($validator->validate(4));
        $this->assertEquals('Value is not valid', (string) $validator->getMessage());
    }

    function testCustomErrorMessages()
    {
        $this->ruleFactory->register('even', '\Latinosoft\Validation\TestingCustomRule', 'This should be even',
            '{label} should be even');

        $validatorWithLabel = $this->ruleFactory->createRule('even', null, null, 'Number');
        $validatorWithLabel->validate(4);
        $this->assertEquals('Number should be even', (string) $validatorWithLabel->getMessage());

        $validator = $validator = $this->ruleFactory->createRule('even');
        $validator->validate(4);
        $this->assertEquals('This should be even', (string) $validator->getMessage());

    }
}
