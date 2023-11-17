<?php

use Sirius\Validation\Rule\LessThan as Rule;


beforeEach(function () {
    $this->rule = new Rule();
});

test('exclusive validation', function () {
    $this->rule->setOption('inclusive', false);
    $this->rule->setOption('max', 100);
    expect($this->rule->validate(100))->toBeFalse();
});

test('validation without a limit', function () {
    expect($this->rule->validate(0))->toBeTrue();
});

test('option normalization for http query string', function () {
    $this->rule = new Rule('max=100&inclusive=false');
    expect($this->rule->validate(100))->toBeFalse();

    $this->rule = new Rule('max=100&inclusive=true');
    expect($this->rule->validate(100))->toBeTrue();
});

test('option normalization for json string', function () {
    $this->rule = new Rule('{"max": 100, "inclusive": false}');
    expect($this->rule->validate(100))->toBeFalse();
});

test('option normalization for csv string', function () {
    $this->rule = new Rule('100,false');
    expect($this->rule->validate(100))->toBeFalse();

    $this->rule = new Rule('100,true');
    expect($this->rule->validate(100))->toBeTrue();
});
