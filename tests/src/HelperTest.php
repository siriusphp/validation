<?php
namespace Latinosoft\Validation;

use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{

    function testOfMethodExists()
    {
        $this->assertTrue(Helper::methodExists('email'));
        $this->assertFalse(Helper::methodExists('nonExistingMethod'));
    }

    function testOfRequired()
    {
        $pool = array(
            'abc' => true,
            1.2   => true,
            ''    => false
        );
        foreach ($pool as $key => $value) {
            $this->assertSame(Helper::required($key), $value);
        }
        $this->assertSame(Helper::required(null), false);
    }

    function testOfTruthy()
    {
        $pool = array(
            'abc' => true,
            1.2   => true,
            0     => false,
            ''    => false
        );
        foreach ($pool as $key => $value) {
            $this->assertSame(Helper::truthy($key), $value);
        }
    }

    function testOfFalsy()
    {
        $pool = array(
            'abc' => false,
            1.2   => false,
            0     => true,
            ''    => true
        );
        foreach ($pool as $key => $value) {
            $this->assertSame(Helper::falsy($key), $value);
        }
    }

    function testOfCallback()
    {
        $this->assertTrue(
            Helper::callback(
                3,
                function ($value) {
                    return $value === 3;
                }
            )
        );
    }

    function testOfEmail()
    {
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
            $this->assertSame(Helper::email($key), $value, $message);
        }
    }

    function testOfNumber()
    {
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
            $this->assertSame(Helper::number($key), $value, $message);
        }
    }

    function testOfInteger()
    {
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
            $this->assertSame(Helper::integer($key), $value, $message);
        }
    }

    function testOfLessThan()
    {
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
            $this->assertSame(Helper::lessThan($sample[0], $sample[1]), $sample[2], $message);
        }
    }

    function testOfGreaterThan()
    {
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
            $this->assertSame(Helper::greaterThan($sample[0], $sample[1]), $sample[2], $message);
        }
    }

    function testOfBetween()
    {
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
            $this->assertSame(Helper::between($sample[0], $sample[1], $sample[2]), $sample[3], $message);
        }
    }

    function testOfExactly()
    {
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
            $this->assertSame(Helper::exactly($sample[0], $sample[1]), $sample[2], $message);
        }
    }

    function testOfNot()
    {
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
            $this->assertSame(Helper::not($sample[0], $sample[1]), $sample[2], $message);
        }
    }

    function testOfAlpha()
    {
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
            $this->assertSame(Helper::alpha($key), $value, $message);
        }
    }

    function testOfAlphanumeric()
    {
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
            $this->assertSame(Helper::alphanumeric($key), $value, $message);
        }
    }

    function testOfAlphanumhyphen()
    {
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
            $this->assertSame(Helper::alphanumhyphen($key), $value, $message);
        }
    }

    function testOfLength()
    {
        $this->assertTrue(Helper::length('abc', 1, 5));
    }

    function testOfMinLength()
    {
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
            $this->assertSame(Helper::minLength($sample[0], $sample[1]), $sample[2], $message);
        }
    }

    function testOfMaxLength()
    {
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
            $this->assertSame(Helper::maxLength($sample[0], $sample[1]), $sample[2], $message);
        }
    }

    function testOfIn()
    {
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
            $this->assertSame(Helper::inList($sample[0], $sample[1]), $sample[2]);
        }
    }

    function testOfNotIn()
    {
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
            $this->assertSame(Helper::notInList($sample[0], $sample[1]), $sample[2]);
        }
    }

    function testOfRegex()
    {
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
            $this->assertSame(Helper::regex($sample[0], $sample[1]), $sample[2]);
        }
    }

    function testOfNotRegex()
    {
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
            $this->assertSame(Helper::notRegex($sample[0], $sample[1]), $sample[2]);
        }
    }

    function testOfEqualToWithContext()
    {
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
            $this->assertSame(
                Helper::equalTo($sample[0], $sample[1], $context),
                $sample[2],
                sprintf("%s %s", $sample[0], $sample[1])
            );
        }
    }

    function testOfNotEqualToWithContext()
    {
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
            $this->assertSame(
                Helper::notEqualTo($sample[0], $sample[1], $context),
                $sample[2],
                sprintf("%s %s", $sample[0], $sample[1])
            );
        }
    }

    function testOfEqualToWithoutContext()
    {
        $this->assertTrue(Helper::equalTo(5, '5'));
        $this->assertFalse(Helper::equalTo(5, 'a'));
    }

    function testOfNotEqualToWithoutContext()
    {
        $this->assertTrue(Helper::NotEqualTo(5, 'a'));
        $this->assertFalse(Helper::NotEqualTo(5, '5'));
    }

    function testOfWebsite()
    {
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
            $this->assertSame(Helper::website($sample[0]), $sample[1]);
        }
    }

    function testOfUrl()
    {
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
            $this->assertSame(Helper::url($sample[0]), $sample[1]);
        }
    }

    function testOfIp()
    {
        $pool = array(
            array(
                '196.168.100.1',
                true
            ),
            array(
                '256.5765.53.21',
                false
            ),
            array(
                '2001:db8:85a3:8d3:1319:8a2e:370:7348',
                true
            ) //IPv6
        );

        foreach ($pool as $sample) {
            $this->assertSame(Helper::ipAddress($sample[0]), $sample[1]);
        }
    }

    function testOfSetMaxSize()
    {
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
            $this->assertSame(Helper::setMaxSize($set, $sample[0]), $sample[1]);
        }
    }

    function testOfSetMinSize()
    {
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
            $this->assertSame(Helper::setMinSize($set, $sample[0]), $sample[1]);
        }
    }

    function testOfSetSize()
    {
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
            $this->assertSame(Helper::setSize($set, $sample[0], $sample[1]), $sample[2]);
        }
    }

    function validationCallback($value, $options = false, $context = null)
    {
        return ($value == 5 and $options = 3 and $context == null);
    }

    function testOfValidAddMethodCalls()
    {
        Helper::addMethod(
            'myValidation',
            array(
                $this,
                'validationCallback'
            )
        );
        $this->assertTrue(Helper::myValidation(5, 3));
        $this->assertFalse(Helper::myValidation(5, 3, 3));
    }

    function testOfInvalidAddMethodCalls()
    {
        $this->expectException('InvalidArgumentException');
        Helper::addMethod('mySecondValidation', 'nonexistantcallback');
        $this->assertTrue(Helper::mySecondValidation(5));
    }

    function testOfFullName()
    {
        $this->assertTrue(Helper::fullName('First Last'));
        $this->assertFalse(Helper::fullName('F Last'));
        $this->assertFalse(Helper::fullName('First L'));
        $this->assertFalse(Helper::fullName('Fi La'));
    }

    function testOfEmailDomain()
    {
        $this->assertTrue(Helper::emailDomain('me@hotmail.com'));
        $this->assertFalse(Helper::emailDomain('me@hotmail.com.not.valid'));
    }

    function testOfDate()
    {
        $this->assertTrue(Helper::date('2012-07-13', 'Y-m-d'));
        $this->assertFalse(Helper::date('2012-07-13', 'Y/m/d'));
    }

    function testOfDateTime()
    {
        $this->assertTrue(Helper::dateTime('2012-07-13 20:00:15', 'Y-m-d H:i:s'));
        $this->assertFalse(Helper::dateTime('2012-07-13'));
    }

    function testOfTime()
    {
        $this->assertTrue(Helper::time('20:00:15', 'H:i:s'));
        $this->assertFalse(Helper::time('20:00:99'));
    }

}
