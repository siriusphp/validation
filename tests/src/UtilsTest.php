<?php

namespace Sirius\Validation;

use Sirius\Validation\Utils;

class UtilsTest extends \PHPUnit_Framework_TestCase  {

    function setUp() {
        $this->data = array(
            'name' => 'John Doe',
            'addresses' => array(
                'billing' => array(
                    'street' => '1st Ave'
                ),
                'shipping' => array(
                    'street' => '1st Boulevar'
                )
            )
        );
    }


    function testOfArrayGetByPath() {
        $this->assertEquals(Utils::arrayGetByPath($this->data, 'name'), $this->data['name']);
        $this->assertEquals(Utils::arrayGetByPath($this->data, 'addresses[shipping][street]'), $this->data['addresses']['shipping']['street']);
        $this->assertEquals(Utils::arrayGetByPath($this->data, 'email'), null);
        $this->assertEquals(Utils::arrayGetByPath($this->data, 'address[shipping][street]'), null);
    }
    
    function testOfArrayGetByPathRoot() {
        $this->assertEquals($this->data, Utils::arrayGetByPath($this->data));
    }

    function testOfArraySetByPath() {
        $this->data = Utils::arraySetBySelector($this->data, 'email', 'my@domain.com');
        $this->assertEquals(Utils::arrayGetByPath($this->data, 'email'), 'my@domain.com');

        $this->data = Utils::arraySetBySelector($this->data, 'newsletters[offers]', true);
        $this->assertEquals(Utils::arrayGetByPath($this->data, 'newsletters'), array('offers' => true));
        $this->data = Utils::arraySetBySelector($this->data, 'addresses[*][state]', 'California');
        $this->assertEquals(Utils::arrayGetByPath($this->data, 'addresses[shipping][state]'), 'California');
        $this->assertEquals(Utils::arrayGetByPath($this->data, 'addresses[billing][state]'), 'California');
    }

    function testOfArraySetBySelectorDoesNotOverwriteTheExistingValues() {
        $this->data = Utils::arraySetBySelector($this->data, 'name', 'Jane Fonda');
        $this->assertEquals(Utils::arrayGetByPath($this->data, 'name'), 'John Doe');
    }

    function testOfArraySetBySelectorEnsuresDataIsArray() {
        $this->data = Utils::arraySetBySelector('string', 'name', 'Jane Fonda');
        $this->assertEquals(Utils::arrayGetByPath($this->data, 'name'), 'Jane Fonda');
    }
    
    function testOfArrayGetBySelectorDeepSearch() {
        $arr = array(
            'people' => array(
                array(
                    'name' => 'John',
                    'address' => array(
                    	'city' => 'New York'
                    )
                ),
                array(
                    'name' => 'Marry',
                    'address' => array(
                        'state' => 'California'
                    )
                ),
            )
        );
        $this->assertEquals(array(
            'people[0][address][city]' => 'New York',
            'people[1][address][city]' => null
        ), Utils::arrayGetBySelector($arr, 'people[*][address][city]'));
    }
    
    function testOfArrayGetBySelectorUsingPath() {
        $arr = array(
            'recipients' => array(
                array('name' => 'John'),
                array('name' => 'Marry', 'email' => 'marry@gmail.com')
            )
        );
        $this->assertEquals(array(
            'recipients[0][email]' => null
        ), Utils::arrayGetBySelector($arr, 'recipients[0][email]'));
        $this->assertEquals(array(
            'recipients[1][email]' => 'marry@gmail.com'
        ), Utils::arrayGetBySelector($arr, 'recipients[1][email]'));
    }
    
    function testOfArrayGetBySelectorWithEndingSelector() {
        $arr = array(
            'lines' => array(
                'quantities' => array(1, 2, 3)
            )
        );
        $this->assertEquals(array(
        	'lines[quantities][0]' => 1,
            'lines[quantities][1]' => 2,
            'lines[quantities][2]' => 3
        ), Utils::arrayGetBySelector($arr, 'lines[quantities][*]'));
    }

    function testOfArrayGetBySelectorWithWrongSelector() {
        $arr = array(
            'lines' => array(
                'quantities' => array(1, 2, 3)
            )
        );
        $this->assertEquals(array(), Utils::arrayGetBySelector($arr, 'recipients[*]'));
    }
}