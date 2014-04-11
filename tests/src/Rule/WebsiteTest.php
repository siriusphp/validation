<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\Rule\Website as Validator;

class WebsiteTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->validator = new Validator();
    }

    function testNonHttpAddresses()
    {
        $this->assertTrue($this->validator->validate('//google.com'));
    }
}
