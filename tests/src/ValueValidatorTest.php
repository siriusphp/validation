<?php

use Sirius\Validation\Rule\GreaterThan;

use \Sirius\Validation\ValueValidator;

beforeEach(function () {
    $this->validator = new ValueValidator();
});

test('adding validation rules regularly', function () {
    $this->validator->add('required')->add('minlength', '{"min":4}',
        '{label} should have at least {min} characters', 'Item');
    $this->validator->validate('ab');
    expect($this->validator->getMessages())->toEqual(array(
        'Item should have at least 4 characters'
    ));
});

test('adding validation rules via strings', function () {
    $this->validator->add('required | minlength({"min":4})({label} should have at least {min} characters)(Item)');
    $this->validator->validate('ab');
    expect($this->validator->getMessages())->toEqual(array(
        'Item should have at least 4 characters'
    ));
});

test('removing validation rules', function () {
    $this->validator->add('required');
    expect($this->validator->validate(null))->toBeFalse();
    $this->validator->remove('required');
    expect($this->validator->validate(null))->toBeTrue();
});

test('removing all rules', function () {
    $this->validator->add('required')->add('minlength', '{"min":4}',
        '{label} should have at least {min} characters', 'Item');
    $this->validator->validate('ab');
    expect($this->validator->getMessages())->toEqual(array(
        'Item should have at least 4 characters'
    ));
    $this->validator->remove(true);
    expect($this->validator->validate(null))->toBeTrue();
});

test('non required rules', function () {
    $this->validator->add('email');
    expect($this->validator->validate(null))->toBeTrue();
    expect($this->validator->validate(''))->toBeTrue();
});

test('default label', function () {
    $this->validator->setLabel('Item');
    $this->validator->add('required')->add('minlength', '{"min":4}',
        '{label} should have at least {min} characters');
    $this->validator->validate('ab');
    expect($this->validator->getMessages())->toEqual(array(
        'Item should have at least 4 characters'
    ));
});

test('parse rule with zero value in csv format', function () {
    $this->validator->add('GreaterThan(0)');

    /** @var GreaterThan $rule */
    foreach ($this->validator->getRules() as $rule) {
        break;
    }

    expect($rule->getOption('min'))->toBe('0');

    $this->validator->validate(1);
    expect($this->validator->getMessages())->toBeEmpty();

    $this->validator->validate(0);
    expect($this->validator->getMessages())->toBeEmpty();

    $this->validator->validate(-1);
    expect($this->validator->getMessages())->not->toBeEmpty();
});
