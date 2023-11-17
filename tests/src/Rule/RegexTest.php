<?php

use Sirius\Validation\Rule\Regex as Rule;


beforeEach(function () {
    $this->rule = new Rule();
});

test('validation without a regex pattern', function () {
    // pattern was not set, everything is valid
    expect($this->rule->validate('abc'))->toBeTrue();
});
