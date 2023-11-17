<?php

use Sirius\Validation\Rule\Email as Rule;


beforeEach(function () {
    $this->rule = new Rule();
});

test('validation', function () {
    expect($this->rule->validate(''))->toBeFalse();
    expect($this->rule->validate('me@domain.com'))->toBeTrue();
});
