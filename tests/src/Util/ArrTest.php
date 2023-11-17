<?php

beforeEach(function () {
    $this->data = array(
        'name'      => 'John Doe',
        'addresses' => array(
            'billing'  => array(
                'street' => '1st Ave'
            ),
            'shipping' => array(
                'street' => '1st Boulevar'
            )
        )
    );
});

use \Sirius\Validation\Util\Arr;

test('array get by path', function () {
    expect($this->data['name'])->toEqual(Arr::getByPath($this->data, 'name'));
    expect($this->data['addresses']['shipping']['street'])->toEqual(Arr::getByPath($this->data, 'addresses[shipping][street]'));
    expect(null)->toEqual(Arr::getByPath($this->data, 'email'));
    expect(null)->toEqual(Arr::getByPath($this->data, 'address[shipping][street]'));
});

test('array get by path root', function () {
    expect(Arr::getByPath($this->data))->toEqual($this->data);
});

test('array set by path', function () {
    $this->data = Arr::setBySelector($this->data, 'email', 'my@domain.com');
    expect('my@domain.com')->toEqual(Arr::getByPath($this->data, 'email'));

    $this->data = Arr::setBySelector($this->data, 'newsletters[offers]', true);
    expect(array( 'offers' => true ))->toEqual(Arr::getByPath($this->data, 'newsletters'));
    $this->data = Arr::setBySelector($this->data, 'addresses[*][state]', 'California');
    expect('California')->toEqual(Arr::getByPath($this->data, 'addresses[shipping][state]'));
    expect('California')->toEqual(Arr::getByPath($this->data, 'addresses[billing][state]'));
});

test('array set by selector does not overwrite the existing values', function () {
    $this->data = Arr::setBySelector($this->data, 'name', 'Jane Fonda');
    expect('John Doe')->toEqual(Arr::getByPath($this->data, 'name'));
});

test('array get by selector deep search', function () {
    $arr = array(
        'people' => array(
            array(
                'name'    => 'John',
                'address' => array(
                    'city' => 'New York'
                )
            ),
            array(
                'name'    => 'Marry',
                'address' => array(
                    'state' => 'California'
                )
            ),
        )
    );
    expect(Arr::getBySelector($arr, 'people[*][address][city]'))->toEqual(array(
        'people[0][address][city]' => 'New York',
        'people[1][address][city]' => null
    ));
});

test('array get by selector using path', function () {
    $arr = array(
        'recipients' => array(
            array( 'name' => 'John' ),
            array( 'name' => 'Marry', 'email' => 'marry@gmail.com' )
        )
    );
    expect(Arr::getBySelector($arr, 'recipients[0][email]'))->toEqual(array(
        'recipients[0][email]' => null
    ));
    expect(Arr::getBySelector($arr, 'recipients[1][email]'))->toEqual(array(
        'recipients[1][email]' => 'marry@gmail.com'
    ));
});

test('array get by selector with ending selector', function () {
    $arr = array(
        'lines' => array(
            'quantities' => array( 1, 2, 3 )
        )
    );
    expect(Arr::getBySelector($arr, 'lines[quantities][*]'))->toEqual(array(
        'lines[quantities][0]' => 1,
        'lines[quantities][1]' => 2,
        'lines[quantities][2]' => 3
    ));
});

test('array get by selector with wrong selector', function () {
    $arr = array(
        'lines' => array(
            'quantities' => array( 1, 2, 3 )
        )
    );
    expect(Arr::getBySelector($arr, 'recipients[*]'))->toEqual(array());
});
