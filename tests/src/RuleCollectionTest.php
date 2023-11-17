<?php

use \Sirius\Validation\Rule\Email;
use \Sirius\Validation\Rule\Required;
use \Sirius\Validation\RuleCollection;

beforeEach(function () {
    $this->collection = new RuleCollection();
});

test('add and remove', function () {
    $this->collection->attach(new Required());
    expect(count($this->collection))->toEqual(1);

    $this->collection->detach(new Required());
    expect(count($this->collection))->toEqual(0);
});

test('iterator', function () {
    $this->collection->attach(new Email);
    $this->collection->attach(new Required);

    $rules = array();
    foreach ($this->collection as $k => $rule) {
        $rules[] = $rule;
    }

    // the required rule should be first
    expect($rules[0] instanceof Required)->toBeTrue();
    expect($rules[1] instanceof Email)->toBeTrue();
});
