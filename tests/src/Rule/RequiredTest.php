<?php

use Sirius\Validation\DataWrapper\ArrayWrapper;
use Sirius\Validation\Rule\Required as Rule;


beforeEach(function () {
    $this->rule = new Rule();
});

test('validation with null', function () {
    expect($this->rule->validate(null))->toBeFalse();
});

test('validation with empty string', function () {
    expect($this->rule->validate(''))->toBeFalse();
});

test('validation with whitespace string', function () {
    expect($this->rule->validate('  '))->toBeTrue();
});
