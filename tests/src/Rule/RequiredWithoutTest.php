<?php

use Sirius\Validation\DataWrapper\ArrayWrapper;
use Sirius\Validation\Rule\RequiredWithout as Rule;


beforeEach(function () {
    $this->rule = new Rule();
    $this->rule->setContext(
        new ArrayWrapper(
            array(
                'item_1' => 'is_present'
            )
        )
    );
});

test('validation without item present', function () {
    $this->rule->setOption(Rule::OPTION_ITEM, 'item_2');
    expect($this->rule->validate('abc'))->toBeTrue();
    expect($this->rule->validate(null))->toBeFalse();
    expect($this->rule->validate(''))->toBeFalse();
});

test('validation with item present', function () {
    $this->rule->setOption(Rule::OPTION_ITEM, 'item_1');
    expect($this->rule->validate('abc'))->toBeTrue();
    expect($this->rule->validate(null))->toBeTrue();
    expect($this->rule->validate(''))->toBeTrue();
});

test('validation with deep items', function () {
    $this->rule->setOption(Rule::OPTION_ITEM, 'lines[*][quantity]');
    $this->rule->setContext(new ArrayWrapper(
            array(
                'lines' => array(
                    0 => array( 'quantity' => null, 'price' => null ),
                    1 => array( 'quantity' => 20, 'price' => null ),
                )
            ))
    );
    expect($this->rule->validate(null, 'lines[0][price]'))->toBeFalse();
    expect($this->rule->validate(null, 'lines[1][price]'))->toBeTrue();
    expect($this->rule->validate('', 'lines[1][price]'))->toBeTrue();
});
