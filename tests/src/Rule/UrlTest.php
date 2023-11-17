<?php

use Sirius\Validation\Rule\Url as Rule;


beforeEach(function () {
    $this->rule = new Rule();
});

test('validation', function () {
    expect($this->rule->validate(''))->toBeFalse();
    expect($this->rule->validate('http://www.google.com'))->toBeTrue();
});
