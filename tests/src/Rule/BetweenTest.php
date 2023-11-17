<?php

use Sirius\Validation\Rule\Between as Rule;


beforeEach(function () {
    $this->rule = new Rule();
});

test('validation', function () {
    $this->rule->setOption('min', 50);
    $this->rule->setOption('max', 100);
    expect($this->rule->validate(40))->toBeFalse();
    expect($this->rule->validate(110))->toBeFalse();
    expect($this->rule->validate(80))->toBeTrue();
});
