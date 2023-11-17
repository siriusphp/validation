<?php

use Sirius\Validation\Rule\Website as Rule;


beforeEach(function () {
    $this->rule = new Rule();
});

test('non http addresses', function () {
    expect($this->rule->validate('//google.com'))->toBeTrue();
});
