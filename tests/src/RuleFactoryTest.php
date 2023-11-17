<?php

use Sirius\Validation\Rule\AbstractRule;
use Sirius\Validation\RuleFactory;

class TestingCustomRule extends AbstractRule
{

    function validate($value, string $valueIdentifier = null): bool
    {
        return (bool)($value % 2);
    }
}

beforeEach(function () {
    $this->ruleFactory = new RuleFactory();
});

test('registration of validator classes', function () {
    $this->ruleFactory->register('even', TestingCustomRule::class);

    $validator = $this->ruleFactory->createRule('even');
    expect($validator instanceof TestingCustomRule)->toBeTrue();
    expect($validator->validate(3))->toBeTrue();
    expect($validator->validate(4))->toBeFalse();
    expect((string)$validator->getMessage())->toEqual('Value is not valid');
});

test('custom error messages', function () {
    $this->ruleFactory->register('even', TestingCustomRule::class, 'This should be even',
        '{label} should be even');

    $validatorWithLabel = $this->ruleFactory->createRule('even', null, null, 'Number');
    $validatorWithLabel->validate(4);
    expect((string)$validatorWithLabel->getMessage())->toEqual('Number should be even');

    $validator = $this->ruleFactory->createRule('even');
    $validator->validate(4);
    expect((string)$validator->getMessage())->toEqual('This should be even');
});
