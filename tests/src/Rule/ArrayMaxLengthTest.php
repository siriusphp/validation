<?php

use Sirius\Validation\Rule\ArrayMaxLength as Rule;


beforeEach(function () {
    $this->rule = new Rule();
});

test('validation without a limit', function () {
    expect($this->rule->validate(array()))->toBeTrue();
});
