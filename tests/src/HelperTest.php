<?php

use \Sirius\Validation\Helper;

test('method exists', function () {
    expect(Helper::methodExists('email'))->toBeTrue();
    expect(Helper::methodExists('nonExistingMethod'))->toBeFalse();
});

test('required', function () {
    $pool = array(
        ['abc', true],
        [1.2, true],
        ['', false]
    );
    foreach ($pool as list($key, $value)) {
        expect($value)->toBe(Helper::required($key));
    }
    expect(false)->toBe(Helper::required(null));
});

test('truthy', function () {
    $pool = array(
        ['abc', true],
        [1.2, true],
        [0, false],
        ['', false],
    );
    foreach ($pool as list($key, $value)) {
        expect($value)->toBe(Helper::truthy($key));
    }
});

test('falsy', function () {
    $pool = array(
        ['abc', false],
        [1.2, false],
        [0, true],
        ['', true],
    );
    foreach ($pool as list($key, $value)) {
        expect($value)->toBe(Helper::falsy($key));
    }
});

test('callback', function () {
    expect(Helper::callback(
        3,
        function ($value) {
            return $value === 3;
        }
    ))->toBeTrue();
});

test('email', function () {
    $pool = array(
        '-fa /lse@gmail.com'          => false,
        '12345@hotmail.com'           => true,
        'xxx.yyyy-zzz@domain.com.noc' => true,
        'weird.name-99-@yahoo.com'    => true,
        'shit'                        => false
    );
    foreach ($pool as $key => $value) {
        if ($value) {
            $message = $key . ' is a valid email';
        } else {
            $message = $key . ' is NOT a valid email';
        }
        expect($value)->toBe(Helper::email($key), $message);
    }
});

test('number', function () {
    $pool = array(
        '1'   => true,
        '1,5' => false,
        '2.5' => true,
        'abc' => false
    );
    foreach ($pool as $key => $value) {
        if ($value) {
            $message = $key . ' is a valid number';
        } else {
            $message = $key . ' is NOT a valid number';
        }
        expect($value)->toBe(Helper::number($key), $message);
    }
});

test('integer', function () {
    $pool = array(
        '1'     => true,
        '12345' => true,
        '1.00'  => true,
        '1.24'  => false
    );
    foreach ($pool as $key => $value) {
        if ($value) {
            $message = $key . ' is a valid integer';
        } else {
            $message = $key . ' is NOT a valid integer';
        }
        expect($value)->toBe(Helper::integer($key), $message);
    }
});

test('less than', function () {
    $pool = array(
        array(
            1,
            0.5,
            false
        ),
        array(
            1,
            1.2,
            true
        )
    );
    foreach ($pool as $sample) {
        if ($sample[2]) {
            $message = $sample[0] . ' is less than ' . $sample[1];
        } else {
            $message = $sample[0] . ' is NOT less than ' . $sample[1];
        }
        expect($sample[2])->toBe(Helper::lessThan($sample[0], $sample[1]), $message);
    }
});

test('greater than', function () {
    $pool = array(
        array(
            1,
            0.5,
            true
        ),
        array(
            1,
            1.2,
            false
        )
    );
    foreach ($pool as $sample) {
        if ($sample[2]) {
            $message = $sample[0] . ' is less than ' . $sample[1];
        } else {
            $message = $sample[0] . ' is NOT less than ' . $sample[1];
        }
        expect($sample[2])->toBe(Helper::greaterThan($sample[0], $sample[1]), $message);
    }
});

test('between', function () {
    $pool = array(
        array(
            1,
            0.5,
            0.8,
            false
        ),
        array(
            1,
            0.9,
            1.2,
            true
        )
    );
    foreach ($pool as $sample) {
        if ($sample[2]) {
            $message = $sample[0] . ' is between ' . $sample[1] . ' and ' . $sample[2];
        } else {
            $message = $sample[0] . ' is NOT between ' . $sample[1] . ' and ' . $sample[2];
        }
        expect($sample[3])->toBe(Helper::between($sample[0], $sample[1], $sample[2]), $message);
    }
});

test('exactly', function () {
    $pool = array(
        array(
            1,
            '1',
            true
        ),
        array(
            1,
            1.0,
            true
        ),
        array(
            1,
            01,
            true
        ),
        array(
            1,
            'a',
            false
        )
    );
    foreach ($pool as $sample) {
        if ($sample[2]) {
            $message = $sample[0] . ' is exactly ' . $sample[1];
        } else {
            $message = $sample[0] . ' is NOT exactly ' . $sample[1];
        }
        expect($sample[2])->toBe(Helper::exactly($sample[0], $sample[1]), $message);
    }
});

test('not', function () {
    $pool = array(
        array(
            1,
            '1',
            false
        ),
        array(
            1,
            1.0,
            false
        ),
        array(
            1,
            01,
            false
        ),
        array(
            1,
            'a',
            true
        )
    );
    foreach ($pool as $sample) {
        if ($sample[2]) {
            $message = $sample[0] . ' is not ' . $sample[1];
        } else {
            $message = $sample[0] . ' is ' . $sample[1];
        }
        expect($sample[2])->toBe(Helper::not($sample[0], $sample[1]), $message);
    }
});

test('alpha', function () {
    $pool = array(
        'Some Random String ' => true,
        '123'                 => false,
        'With other chars :'  => false
    );
    foreach ($pool as $key => $value) {
        if ($value) {
            $message = $key . ' is a alphabetic';
        } else {
            $message = $key . ' is NOT a alphabetic';
        }
        expect($value)->toBe(Helper::alpha($key), $message);
    }
});

test('alphanumeric', function () {
    $pool = array(
        'Some Random String ' => true,
        'Letters and 123'     => true,
        'With other chars :'  => false
    );
    foreach ($pool as $key => $value) {
        if ($value) {
            $message = $key . ' is a alphanumeric';
        } else {
            $message = $key . ' is NOT a alphanumeric';
        }
        expect($value)->toBe(Helper::alphanumeric($key), $message);
    }
});

test('alphanumhyphen', function () {
    $pool = array(
        'Some Random String '        => true,
        'Letters and 123'            => true,
        'With other hyphens _ -'     => true,
        '? - this is not acceptable' => false
    );
    foreach ($pool as $key => $value) {
        if ($value) {
            $message = $key . ' is a alphanumhyphen';
        } else {
            $message = $key . ' is NOT a alphanumhyphen';
        }
        expect($value)->toBe(Helper::alphanumhyphen($key), $message);
    }
});

test('length', function () {
    expect(Helper::length('abc', 1, 5))->toBeTrue();
});

test('min length', function () {
    $pool = array(
        array(
            'abcde',
            7,
            false
        ),
        array(
            'abcde',
            4,
            true
        )
    );
    foreach ($pool as $sample) {
        if ($sample[2]) {
            $message = $sample[0] . ' has more than ' . $sample[1] . ' characters';
        } else {
            $message = $sample[0] . ' does NOT have more than ' . $sample[1] . ' characters';
        }
        expect($sample[2])->toBe(Helper::minLength($sample[0], $sample[1]), $message);
    }
});

test('max length', function () {
    $pool = array(
        array(
            'abcde',
            4,
            false
        ),
        array(
            'abcde',
            6,
            true
        )
    );
    foreach ($pool as $sample) {
        if ($sample[2]) {
            $message = $sample[0] . ' has less than ' . $sample[1] . ' characters';
        } else {
            $message = $sample[0] . ' does NOT have less than ' . $sample[1] . ' characters';
        }
        expect($sample[2])->toBe(Helper::maxLength($sample[0], $sample[1]), $message);
    }
});

test('in', function () {
    $pool = array(
        array(
            '6',
            array(
                '5',
                '8'
            ),
            false
        ),
        array(
            '5',
            array(
                '5',
                '8'
            ),
            true
        )
    );
    foreach ($pool as $sample) {
        expect($sample[2])->toBe(Helper::inList($sample[0], $sample[1]));
    }
});

test('not in', function () {
    $pool = array(
        array(
            '5',
            array(
                '5',
                '8'
            ),
            false
        ),
        array(
            '6',
            array(
                '5',
                '8'
            ),
            true
        )
    );
    foreach ($pool as $sample) {
        expect($sample[2])->toBe(Helper::notInList($sample[0], $sample[1]));
    }
});

test('regex', function () {
    $pool = array(
        array(
            'abc',
            '/[0-9]+/',
            false
        ),
        array(
            '123',
            '/[0-9]+/',
            true
        )
    );
    foreach ($pool as $sample) {
        expect($sample[2])->toBe(Helper::regex($sample[0], $sample[1]));
    }
});

test('not regex', function () {
    $pool = array(
        array(
            'abc',
            '/[0-9]+/',
            true
        ),
        array(
            '123',
            '/[0-9]+/',
            false
        )
    );
    foreach ($pool as $sample) {
        expect($sample[2])->toBe(Helper::notRegex($sample[0], $sample[1]));
    }
});

test('equal to with context', function () {
    $pool    = array(
        array(
            'value',
            'element_1',
            true
        ),
        array(
            'value',
            'element_2',
            false
        ),
        array(
            'new value',
            'element_3[sub_element_1][sub_element_2]',
            true
        )
    );
    $context = array(
        'element_1' => 'value',
        'element_2' => 'another_value',
        'element_3' => array(
            'sub_element_1' => array(
                'sub_element_2' => 'new value'
            )
        )
    );
    foreach ($pool as $sample) {
        expect($sample[2])->toBe(Helper::equalTo($sample[0], $sample[1], $context), sprintf("%s %s", $sample[0], $sample[1]));
    }
});

test('not equal to with context', function () {
    $pool    = array(
        array(
            'value',
            'element_1',
            false
        ),
        array(
            'value',
            'element_2',
            true
        ),
        array(
            'new value',
            'element_3[sub_element_1][sub_element_2]',
            false
        )
    );
    $context = array(
        'element_1' => 'value',
        'element_2' => 'another_value',
        'element_3' => array(
            'sub_element_1' => array(
                'sub_element_2' => 'new value'
            )
        )
    );
    foreach ($pool as $sample) {
        expect($sample[2])->toBe(Helper::notEqualTo($sample[0], $sample[1], $context), sprintf("%s %s", $sample[0], $sample[1]));
    }
});

test('equal to without context', function () {
    expect(Helper::equalTo(5, '5'))->toBeTrue();
    expect(Helper::equalTo(5, 'a'))->toBeFalse();
});

test('not equal to without context', function () {
    expect(Helper::NotEqualTo(5, 'a'))->toBeTrue();
    expect(Helper::NotEqualTo(5, '5'))->toBeFalse();
});

test('website', function () {
    $pool = array(
        array(
            'https://www.domain.co.uk/',
            true
        ),
        array(
            'https://www.domain.co.uk/folder/page.html?var=value#!fragment',
            true
        ),
        array(
            '123',
            false
        )
    );
    foreach ($pool as $sample) {
        expect($sample[1])->toBe(Helper::website($sample[0]));
    }
});

test('url', function () {
    $pool = array(
        array(
            'ftp://ftp.domain.co.uk/',
            true
        ),
        array(
            'ftp://username:password@domain.co.uk/folder/',
            true
        ),
        array(
            '123',
            false
        )
    );
    foreach ($pool as $sample) {
        expect($sample[1])->toBe(Helper::url($sample[0]));
    }
});

test('ip', function () {
    $pool = array(
        array(
            '196.168.100.1',
            true
        ),
        array(
            '256.576a.53.21',
            false
        ),
        array(
            '2002:51c4:7c3c:0000:0000:0000:0000:0000',
            true
        ) //IPv6
    );

    foreach ($pool as $sample) {
        expect($sample[1])->toBe(Helper::ipAddress($sample[0]), $sample[0]);
    }
});

test('set max size', function () {
    $set  = array(
        'element_1' => 'value',
        'element_2' => 'another_value',
        'element_3' => array(
            'sub_element_1' => 'new value'
        )
    );
    $pool = array(
        array(
            '4',
            true
        ),
        array(
            '2',
            false
        )
    );
    foreach ($pool as $sample) {
        expect($sample[1])->toBe(Helper::setMaxSize($set, $sample[0]));
    }
});

test('set min size', function () {
    $set  = array(
        'element_1' => 'value',
        'element_2' => 'another_value',
        'element_3' => array(
            'sub_element_1' => 'new value'
        )
    );
    $pool = array(
        array(
            '4',
            false
        ),
        array(
            '2',
            true
        )
    );
    foreach ($pool as $sample) {
        expect($sample[1])->toBe(Helper::setMinSize($set, $sample[0]));
    }
});

test('set size', function () {
    $set  = array(
        'element_1' => 'value',
        'element_2' => 'another_value',
        'element_3' => array(
            'sub_element_1' => 'new value'
        )
    );
    $pool = array(
        array(
            2,
            5,
            true
        ),
        array(
            6,
            8,
            false
        )
    );
    foreach ($pool as $sample) {
        expect($sample[2])->toBe(Helper::setSize($set, $sample[0], $sample[1]));
    }
});

function validationCallback($value, $options = false, $context = null)
{
    return ($value == 5 and $options = 3 and $context == null);
}

test('valid add method calls', function () {
    Helper::addMethod(
        'myValidation',
        'validationCallback'
    );
    expect(Helper::myValidation(5, 3))->toBeTrue();
    expect(Helper::myValidation(5, 3, 3))->toBeFalse();
});

test('invalid add method calls', function () {
    $this->expectException('InvalidArgumentException');
    Helper::addMethod('mySecondValidation', 'nonexistantcallback');
    expect(Helper::mySecondValidation(5))->toBeTrue();
});

test('full name', function () {
    expect(Helper::fullName('First Last'))->toBeTrue();
    expect(Helper::fullName('F Last'))->toBeFalse();
    expect(Helper::fullName('First L'))->toBeFalse();
    expect(Helper::fullName('Fi La'))->toBeFalse();
});

test('email domain', function () {
    expect(Helper::emailDomain('me@hotmail.com'))->toBeTrue();
    expect(Helper::emailDomain('me@hotmail.com.not.valid'))->toBeFalse();
});

test('date', function () {
    expect(Helper::date('2012-07-13', 'Y-m-d'))->toBeTrue();
    expect(Helper::date('2012-07-13', 'Y/m/d'))->toBeFalse();
});

test('date time', function () {
    expect(Helper::dateTime('2012-07-13 20:00:15', 'Y-m-d H:i:s'))->toBeTrue();
    expect(Helper::dateTime('2012-07-13'))->toBeFalse();
});

test('time', function () {
    expect(Helper::time('20:00:15', 'H:i:s'))->toBeTrue();
    expect(Helper::time('20:00:99'))->toBeFalse();
});
