<?php

namespace Sirius\Validation\Test;

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
}