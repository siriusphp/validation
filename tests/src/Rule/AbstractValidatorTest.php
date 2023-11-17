<?php

use Sirius\Validation\ErrorMessage;
use \Sirius\Validation\Rule\AbstractRule;
class FakeRule extends AbstractRule
{
    function validate($value, string $valueIdentifier = null): bool
    {
        $this->value = $value;
        $this->success = (bool)$value && isset($this->context) && $this->context->getItemValue('key');

        return $this->success;
    }
}

beforeEach(function () {
    $this->rule = new FakeRule();
});

test('error message prototype', function () {
    // we always have an error message prototype
    expect($this->rule->getErrorMessagePrototype())->toBeInstanceOf(ErrorMessage::class);
    $proto = new ErrorMessage('Not valid');
    $this->rule->setErrorMessagePrototype($proto);
    expect((string) $this->rule->getErrorMessagePrototype())->toEqual('Not valid');
});

test('message is generated correctly', function () {
    $this->rule->setOption('label', 'Accept');
    $this->rule->setMessageTemplate('Field "{label}" must be true, {value} was provided');
    $this->rule->validate('false');
    expect((string) $this->rule->getMessage())->toEqual('Field "Accept" must be true, false was provided');
});

test('no message when validation passes', function () {
    $this->rule->setContext(array( 'key' => true ));
    expect($this->rule->validate(true))->toBeTrue();
    expect($this->rule->getMessage())->toBeNull();
});

test('context', function () {
    expect($this->rule->validate(true))->toBeFalse();
    $this->rule->setContext(array( 'key' => true ));
    expect($this->rule->validate(true))->toBeTrue();
});

test('error message template is used', function () {
    $this->rule->setMessageTemplate('Custom message');
    expect((string) $this->rule->getPotentialMessage())->toEqual('Custom message');
});

test('error thrown on invalid context', function () {
    $this->expectException('\InvalidArgumentException');
    $this->rule->setContext(new \stdClass());
});

test('get option', function () {
    $this->rule->setOption('label', 'Accept');
    expect($this->rule->getOption('label'))->toEqual('Accept');
    expect($this->rule->getOption('notExist'))->toBeNull();
});
