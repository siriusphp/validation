<?php

use Sirius\Validation\Rule\GreaterThan as Rule;


beforeEach(function () {
    $this->rule = new Rule();
});

test('default options', function () {
    expect($this->rule->getOption('min'))->toBeNull();
    expect($this->rule->getOption('inclusive'))->toBeTrue();
});

test('exclusive validation', function () {
    $this->rule->setOption('inclusive', false);
    $this->rule->setOption('min', 100);
    expect($this->rule->validate(100))->toBeFalse();
});

test('validation without a limit', function () {
    expect($this->rule->validate(0))->toBeTrue();
});

test('construct csv format min zero and inclusive false', function () {
    $this->rule = new Rule('0,false');
    expect($this->rule->getOption('min'))->toBe('0');
    expect($this->rule->getOption('inclusive'))->toBe(false);
});

test('construct with min value zero query string format', function () {
    $this->rule = new Rule('min=0');
    expect($this->rule->getOption('min'))->toBe('0');
});

test('construct with min value zero csv format', function () {
    $this->rule = new Rule('0');
    expect($this->rule->getOption('min'))->toBe('0');
});
