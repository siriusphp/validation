<?php

use Sirius\Validation\DataWrapper\ArrayWrapper;
use Sirius\Validation\Rule\RequiredWhen as Rule;


beforeEach(function () {
    $this->rule = new Rule();
});

test('validation with item valid', function () {
    $this->rule->setOption(Rule::OPTION_ITEM, 'email');
    $this->rule->setOption(Rule::OPTION_RULE, 'Email');
    $this->rule->setContext(
        new ArrayWrapper(
            array(
                'email' => 'me@domain.com'
            )
        )
    );
    expect($this->rule->validate('abc'))->toBeTrue();
    expect($this->rule->validate(null))->toBeFalse();
    expect($this->rule->validate(''))->toBeFalse();
});

test('validation with item not valid', function () {
    $this->rule->setOption(Rule::OPTION_ITEM, 'email');
    $this->rule->setOption(Rule::OPTION_RULE, 'Sirius\Validation\Rule\Email');
    $this->rule->setContext(
        new ArrayWrapper(
            array(
                'email' => 'not_a_valid_email'
            )
        )
    );
    expect($this->rule->validate('abc'))->toBeTrue();
    expect($this->rule->validate(null))->toBeTrue();
    expect($this->rule->validate(''))->toBeTrue();
});

test('validation without item', function () {
    $this->rule->setOption(Rule::OPTION_RULE, 'Sirius\Validation\Rule\Email');
    $this->rule->setContext(
        new ArrayWrapper(
            array(
                'email' => 'not_a_valid_email'
            )
        )
    );
    expect($this->rule->validate('abc'))->toBeTrue();
    expect($this->rule->validate(null))->toBeTrue();
    expect($this->rule->validate(''))->toBeTrue();
});

test('item rule set as rule object', function () {
    $this->rule->setOption(Rule::OPTION_ITEM, 'email');
    $this->rule->setOption(Rule::OPTION_RULE, new \Sirius\Validation\Rule\Email);
    $this->rule->setContext(
        new ArrayWrapper(
            array(
                'email' => 'me@domain.com'
            )
        )
    );
    expect($this->rule->validate('abc'))->toBeTrue();
    expect($this->rule->validate(null))->toBeFalse();
    expect($this->rule->validate(''))->toBeFalse();
});

test('exception thrown on invalid item rule', function () {
    $this->expectException('\InvalidArgumentException');
    $this->rule->setOption(Rule::OPTION_ITEM, 'email');
    $this->rule->setOption(Rule::OPTION_RULE, new \stdClass());
    $this->rule->setContext(
        new ArrayWrapper(
            array(
                'email' => 'me@domain.com'
            )
        )
    );
    expect($this->rule->validate('abc'))->toBeTrue();
});
