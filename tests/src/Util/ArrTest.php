<?php

namespace Latinosoft\Validation\Util;

class ArrTest extends \PHPUnit\Framework\TestCase
{

    function setUp(): void
    {
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
    }


    function testOfArrayGetByPath()
    {
        $this->assertEquals(Arr::getByPath($this->data, 'name'), $this->data['name']);
        $this->assertEquals(
            Arr::getByPath($this->data, 'addresses[shipping][street]'),
            $this->data['addresses']['shipping']['street']
        );
        $this->assertEquals(Arr::getByPath($this->data, 'email'), null);
        $this->assertEquals(Arr::getByPath($this->data, 'address[shipping][street]'), null);
    }

    function testOfArrayGetByPathRoot()
    {
        $this->assertEquals($this->data, Arr::getByPath($this->data));
    }

    function testOfArraySetByPath()
    {
        $this->data = Arr::setBySelector($this->data, 'email', 'my@domain.com');
        $this->assertEquals(Arr::getByPath($this->data, 'email'), 'my@domain.com');

        $this->data = Arr::setBySelector($this->data, 'newsletters[offers]', true);
        $this->assertEquals(Arr::getByPath($this->data, 'newsletters'), array( 'offers' => true ));
        $this->data = Arr::setBySelector($this->data, 'addresses[*][state]', 'California');
        $this->assertEquals(Arr::getByPath($this->data, 'addresses[shipping][state]'), 'California');
        $this->assertEquals(Arr::getByPath($this->data, 'addresses[billing][state]'), 'California');
    }

    function testOfArraySetBySelectorDoesNotOverwriteTheExistingValues()
    {
        $this->data = Arr::setBySelector($this->data, 'name', 'Jane Fonda');
        $this->assertEquals(Arr::getByPath($this->data, 'name'), 'John Doe');
    }

    function testOfArraySetBySelectorEnsuresDataIsArray()
    {
        $this->data = Arr::setBySelector('string', 'name', 'Jane Fonda');
        $this->assertEquals(Arr::getByPath($this->data, 'name'), 'Jane Fonda');
    }

    function testOfArrayGetBySelectorDeepSearch()
    {
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
        $this->assertEquals(
            array(
                'people[0][address][city]' => 'New York',
                'people[1][address][city]' => null
            ),
            Arr::getBySelector($arr, 'people[*][address][city]')
        );
    }

    function testOfArrayGetBySelectorUsingPath()
    {
        $arr = array(
            'recipients' => array(
                array( 'name' => 'John' ),
                array( 'name' => 'Marry', 'email' => 'marry@gmail.com' )
            )
        );
        $this->assertEquals(
            array(
                'recipients[0][email]' => null
            ),
            Arr::getBySelector($arr, 'recipients[0][email]')
        );
        $this->assertEquals(
            array(
                'recipients[1][email]' => 'marry@gmail.com'
            ),
            Arr::getBySelector($arr, 'recipients[1][email]')
        );
    }

    function testOfArrayGetBySelectorWithEndingSelector()
    {
        $arr = array(
            'lines' => array(
                'quantities' => array( 1, 2, 3 )
            )
        );
        $this->assertEquals(
            array(
                'lines[quantities][0]' => 1,
                'lines[quantities][1]' => 2,
                'lines[quantities][2]' => 3
            ),
            Arr::getBySelector($arr, 'lines[quantities][*]')
        );
    }

    function testOfArrayGetBySelectorWithWrongSelector()
    {
        $arr = array(
            'lines' => array(
                'quantities' => array( 1, 2, 3 )
            )
        );
        $this->assertEquals(array(), Arr::getBySelector($arr, 'recipients[*]'));
    }
}
