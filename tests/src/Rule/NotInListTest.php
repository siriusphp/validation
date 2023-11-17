<?php

use Sirius\Validation\Rule\NotInList as Rule;


beforeEach(function () {
    $this->rule = new Rule();
});

test('validation without a list of forbidden values', function () {
    expect($this->rule->validate('abc'))->toBeTrue();
});
