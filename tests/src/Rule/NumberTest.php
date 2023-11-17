<?php

use Sirius\Validation\Rule\Number as Rule;


beforeEach(function () {
    $this->rule = new Rule();
});

test('validation', function () {
    expect($this->rule->validate('0'))->toBeTrue();
    expect($this->rule->validate('0.3'))->toBeTrue();
    expect($this->rule->validate('0,3'))->toBeFalse();
});
