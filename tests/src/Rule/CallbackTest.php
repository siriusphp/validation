<?php

use Sirius\Validation\Rule\Callback as Rule;


beforeEach(function () {
    $this->rule = new Rule();
});

test('validation without a valid callback', function () {
    $this->rule->setOption(Rule::OPTION_CALLBACK, 'ssss');
    expect($this->rule->validate('abc'))->toBeTrue();
});

test('get unique id for callbacks as strings', function () {
    $this->rule->setOption(Rule::OPTION_CALLBACK, 'is_int');
    expect(strpos($this->rule->getUniqueId(), '|is_int') !== false)->toBeTrue();

    $this->rule->setOption(Rule::OPTION_CALLBACK, 'Class::method');
    expect(strpos($this->rule->getUniqueId(), '|Class::method') !== false)->toBeTrue();
});

test('get unique id for callbacks as arrays', function () {
    $this->rule->setOption(Rule::OPTION_CALLBACK, array( 'Class', 'method' ));
    expect(strpos($this->rule->getUniqueId(), '|Class::method') !== false)->toBeTrue();

    $this->rule->setOption(Rule::OPTION_CALLBACK, array( $this, 'setUp' ));
    expect(strpos($this->rule->getUniqueId(), '->setUp') !== false)->toBeTrue();
});

test('get unique id for callbacks with arguments', function () {
    $this->rule->setOption(Rule::OPTION_CALLBACK, 'is_int');
    $this->rule->setOption(Rule::OPTION_ARGUMENTS, array( 'b' => 2, 'a' => 1 ));

    // arguments should be sorted by key so test for that too
    expect(strpos($this->rule->getUniqueId(), '|{"a":1,"b":2}') !== false)->toBeTrue();
});

test('get unique id for closures', function () {
    $this->rule->setOption(
        Rule::OPTION_CALLBACK,
        function ($value, $valueIdentifier) {
            return true;
        }
    );
    expect($this->rule->getUniqueId())->not->toBeNull();
});
