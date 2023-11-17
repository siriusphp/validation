<?php

use Sirius\Validation\ErrorMessage;
use Sirius\Validation\RuleFactory;
use Sirius\Validation\Validator;

function fakeValidationFunction()
{
    return false;
}

class FakeObject
{
    function toArray()
    {
        return get_object_vars($this);
    }
}

beforeEach(function () {
    $this->validator = new Validator(new RuleFactory, new ErrorMessage);
});

test('if messages can be set and cleared', function () {
    expect(count($this->validator->getMessages()))->toEqual(0);

    // add empty message does nothing
    $this->validator->addMessage('field_1');
    expect(count($this->validator->getMessages()))->toEqual(0);

    $this->validator->addMessage('field_1', 'Field is required');
    $this->validator->addMessage('field_2', 'Field should be an email');
    expect(count($this->validator->getMessages()))->toEqual(2);

    $this->validator->clearMessages('field_1');
    expect(count($this->validator->getMessages()))->toEqual(1);
    $this->validator->clearMessages();
    expect(count($this->validator->getMessages()))->toEqual(0);
});

test('exception thrown when the data is not an array', function () {
    $this->expectException('InvalidArgumentException');
    $this->validator->validate('string');
    $this->validator->validate(false);
});

test('if validate executes', function () {
    $this->validator
        ->add('field_1', 'Required', null)
        ->add('field_2', 'Email', null, 'This field should be an email');

    expect($this->validator->validate(
        array(
            'field_1' => 'exists',
            'field_2' => 'not'
        )
    ))->toBeFalse();

    $this->validator->validate(
        array(
            'field_1' => 'exists',
            'field_2' => 'me@domain.com'
        )
    );

    // execute the validation again without data
    $this->validator->validate();
    expect(count($this->validator->getMessages()))->toEqual(0);
});

test('if missing items validate against the required rule', function () {
    $this->validator->add('item', 'required', null, 'This field is required');
    $this->validator->add('items[subitem]', 'required', null, 'This field is required');
    $this->validator->setData(array());
    $this->validator->validate();
    expect(array('This field is required'))->toEqual($this->validator->getMessages('item'));
    expect(array('This field is required'))->toEqual($this->validator->getMessages('items[subitem]'));
});

test('different data formats', function () {
    $this->validator->add('email', 'email');

    // test array objects
    $data = new \ArrayObject(array(), \ArrayObject::ARRAY_AS_PROPS);
    $data->email = 'not_an_email';

    $this->validator->validate($data);
    expect(count($this->validator->getMessages('email')))->toEqual(1);

    // test objects with a 'toArray' method
    $data = new FakeObject();
    $data->email = 'not_an_email';
    $this->validator->validate($data);
    expect(count($this->validator->getMessages('email')))->toEqual(1);
});

test('if exception is thrown on invalid rules', function () {
    $this->expectException('\InvalidArgumentException');
    $this->validator->add('random_string');
});

test('adding multiple rules at once', function () {
    $this->validator->add(
        array(
            'item' => array(
                'required',
                array('minlength', 'min=4', '{label} should have at least {min} characters', 'Item')
            ),
            'itema' => array('required', 'minLength(min=8)', 'required'),
            'itemb' => 'required'
        )
    );
    $this->validator->validate(
        array(
            'item' => 'ab',
            'itema' => 'abc'
        )
    );
    expect($this->validator->getMessages('item'))->toEqual(array('Item should have at least 4 characters'));
    expect($this->validator->getMessages('itema'))->toEqual(array('This input should have at least 8 characters'));
    expect($this->validator->getMessages('itemb'))->toEqual(array('This field is required'));
});

test('adding validation rules via strings without label arg', function () {
    $this->validator
        // mixed rules in 1 string
        ->add('item:Item', 'required | minLength({"min":4})')
        // validator options as a QUERY string
        ->add('itema:Item', 'minLength', 'min=8')
        // validator without options and custom message
        ->add('itemb:Item B', 'required')
        // validator with defaults
        ->add('itemc', 'email');
    $this->validator->validate(array('item' => 'ab', 'itema' => 'abc', 'itemc' => 'abc'));
    expect(array((string)$this->validator->getMessages('item')[0]))->toEqual(array('Item should have at least 4 characters'));
    expect($this->validator->getMessages('itema'))->toEqual(array('Item should have at least 8 characters'));
    expect($this->validator->getMessages('itemb'))->toEqual(array('Item B is required'));
    expect($this->validator->getMessages('itemc'))->toEqual(array('This input must be a valid email address'));
});

test('adding validation rules via strings', function () {
    $this->validator
        // mixed rules in 1 string
        ->add('item', 'required | minLength({"min":4})({label} should have at least {min} characters)(Item)')
        // validator options as a QUERY string
        ->add('itema', 'minLength', 'min=8', '{label} should have at least {min} characters', 'Item')
        // validator without options and custom message
        ->add('itemb', 'required()(Item B is required)')
        // validator with defaults
        ->add('itemc', 'email');
    $this->validator->validate(array('item' => 'ab', 'itema' => 'abc', 'itemc' => 'abc'));
    expect($this->validator->getMessages('item'))->toEqual(array('Item should have at least 4 characters'));
    expect($this->validator->getMessages('itema'))->toEqual(array('Item should have at least 8 characters'));
    expect($this->validator->getMessages('itemb'))->toEqual(array('Item B is required'));
    expect($this->validator->getMessages('itemc'))->toEqual(array('This input must be a valid email address'));
});

test('exception on invalid validator options', function () {
    $this->expectException('\InvalidArgumentException');
    $this->validator->add('item', 'required', new \stdClass());
});

function fakeValidationMethod($value)
{
    return false;
}

function fakeStaticValidationMethod($value, $return = false)
{
    return $return;
}

test('callback validators', function () {
    $this->validator->add('function', 'fakeValidationFunction');

    // this will return true
    $this->validator->validate(
        array(
            'function' => true,
        )
    );
    expect(count($this->validator->getMessages()))->toEqual(1);
});

test('removing validation rules', function () {
    $this->validator->add('item', 'required');
    expect($this->validator->validate(array()))->toBeFalse();

    $this->validator->remove('item', 'required');
    expect($this->validator->validate(array()))->toBeTrue();
});

test('removing all validation rules', function () {
    $this->validator->remove('item', true);
    $this->validator->add('item', 'required');
    $this->validator->add('item', 'email');
    $this->validator->setData(array());
    expect($this->validator->validate())->toBeFalse();

    $this->validator->remove('item', true);
    $rules = $this->validator->getRules();
    expect(0)->toEqual(count($rules['item']->getRules()));
    expect($this->validator->validate(array()))->toBeTrue();
});

test('matching rules', function () {
    $this->validator
        ->add('items[*][key]', 'email', null, 'Key must be an email');
    $this->validator->validate(
        array(
            'items' => array(
                array('key' => 'sss'),
                array('key' => 'sss')
            )
        )
    );
    expect($this->validator->getMessages('items[0][key]'))->toEqual(array('Key must be an email'));
    expect($this->validator->getMessages('items[1][key]'))->toEqual(array('Key must be an email'));
});

test('if parameters are sent to validation methods', function () {
    $this->validator
        ->add('a', 'email', array(0, 1), 'This should be an email')
        ->add('b', 'email', array(0, 1, 2), 'This should be an email')
        ->add('c', 'email', array(0, 1, 2, 3), 'This should be an email');
    $this->validator->validate(array('a' => 'a', 'b' => 'b', 'c' => 'c'));
    $messages = $this->validator->getMessages();
    foreach (array('a', 'b', 'c') as $k) {
        expect(count($messages[$k]))->toEqual(1);
    }
});

test('if exception is thrown for invalid validation methods', function () {
    $this->expectException('\InvalidArgumentException');
    $this->validator->add('item', 'faker');
    $this->validator->validate(array('item' => true));
});

test('empty array validation', function () {
    $this->validator->add(array(
        'a' => array('required'),
        'b' => array('required')
    ));
    $this->validator->validate(array());
    expect(count($this->validator->getMessages()))->toEqual(2);
});

test('validation require conditional', function () {
    $this->validator->add(array(
        'a' => array('number', 'requiredWith(b)'),
        'b' => array('number', 'requiredWith(a)')
    ));
    expect($this->validator->validate(array()))->toBeTrue();
});
