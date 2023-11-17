<?php

use Sirius\Validation\DataWrapper\ArrayWrapper;
use Sirius\Validation\Rule\Matching as Rule;


beforeEach(function () {
    $this->rule = new Rule();
    $this->rule->setContext(
        new ArrayWrapper(
            array(
                'password' => 'secret'
            )
        )
    );
});

test('validation with item present', function () {
    $this->rule->setOption(Rule::OPTION_ITEM, 'password');
    expect($this->rule->validate('secret'))->toBeTrue();
    expect($this->rule->validate('abc'))->toBeFalse();
});

test('validation without item present', function () {
    expect($this->rule->validate('abc'))->toBeTrue();
    expect($this->rule->validate(null))->toBeTrue();
});
