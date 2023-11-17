<?php

use Sirius\Validation\DataWrapper\ArrayWrapper;
use Sirius\Validation\Rule\RequiredWith as Rule;


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

test('validation with item present', function () {
    $this->rule->setOption(Rule::OPTION_ITEM, 'item_1');
    expect($this->rule->validate('abc'))->toBeTrue();
    expect($this->rule->validate(null))->toBeFalse();
    expect($this->rule->validate(''))->toBeFalse();
});

test('validation without item present', function () {
    $this->rule->setOption(Rule::OPTION_ITEM, 'item_2');
    expect($this->rule->validate('abc'))->toBeTrue();
    expect($this->rule->validate(null))->toBeTrue();
});

test('validation with deep items', function () {
    $this->rule->setOption(Rule::OPTION_ITEM, 'lines[*][quantity]');
    $this->rule->setContext(new ArrayWrapper(
            array(
                'lines' => array(
                    0 => array( 'quantity' => 10, 'price' => 10 ),
                    1 => array( 'quantity' => 20, 'price' => null ),
                )
            ))
    );
    expect($this->rule->validate(10, 'lines[0][price]'))->toBeTrue();
    expect($this->rule->validate(null, 'lines[1][price]'))->toBeFalse();
    expect($this->rule->validate('', 'lines[1][price]'))->toBeFalse();
});
