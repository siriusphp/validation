<?php

use Sirius\Validation\Rule\Integer as Rule;


beforeEach(function () {
    $this->rule = new Rule();
});

test('validation', function () {
    expect($this->rule->validate('0'))->toBeTrue();
    expect($this->rule->validate('10'))->toBeTrue();
    expect($this->rule->validate('10.3'))->toBeFalse();
});
