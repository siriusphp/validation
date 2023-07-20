<?php

namespace Sirius\Validation;

use Sirius\Validation\Rule\AbstractRule;

class TestingCustomRule extends AbstractRule
{

    function validate($value, string $valueIdentifier = null):bool
    {
        return (bool) ($value % 2);
    }
}

class RuleFactoryTest extends \PHPUnit\Framework\TestCase
{

    protected function setUp(): void
    {
        $this->ruleFactory = new RuleFactory();
    }

    function testRegistrationOfValidatorClasses(): void
    {
        $this->ruleFactory->register('even', '\Sirius\Validation\TestingCustomRule');

        $validator = $this->ruleFactory->createRule('even');
        $this->assertTrue($validator instanceof TestingCustomRule);
        $this->assertTrue($validator->validate(3));
        $this->assertFalse($validator->validate(4));
        $this->assertEquals('Value is not valid', (string) $validator->getMessage());
    }

    function testCustomErrorMessages(): void
    {
        $this->ruleFactory->register('even', '\Sirius\Validation\TestingCustomRule', 'This should be even',
            '{label} should be even');

        $validatorWithLabel = $this->ruleFactory->createRule('even', null, null, 'Number');
        $validatorWithLabel->validate(4);
        $this->assertEquals('Number should be even', (string) $validatorWithLabel->getMessage());

        $validator = $validator = $this->ruleFactory->createRule('even');
        $validator->validate(4);
        $this->assertEquals('This should be even', (string) $validator->getMessage());

    }
}
