<?php

use Sirius\Validation\Rule\ArrayMinLength as Rule;


beforeEach(function () {
    $this->rule = new Rule();
});

test('validation without a limit', function () {
    expect($this->rule->validate(array()))->toBeTrue();
});
