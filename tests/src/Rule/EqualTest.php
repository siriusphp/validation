<?php

use Sirius\Validation\Rule\Equal as Rule;


beforeEach(function () {
    $this->rule = new Rule();
});

test('validation with option set', function () {
    $this->rule->setOption(Rule::OPTION_VALUE, '123');
    expect($this->rule->validate('123'))->toBeTrue();
    expect($this->rule->validate('abc'))->toBeFalse();
});

test('validation without option set', function () {
    expect($this->rule->validate('abc'))->toBeTrue();
    expect($this->rule->validate(null))->toBeTrue();
});
