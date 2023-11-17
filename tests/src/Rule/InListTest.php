<?php

use Sirius\Validation\Rule\InList as Rule;


beforeEach(function () {
    $this->rule = new Rule();
});

test('validation without a l ist of acceptable values', function () {
    expect($this->rule->validate('abc'))->toBeTrue();
});
