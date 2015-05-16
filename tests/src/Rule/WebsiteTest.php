<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\Rule\Website as Rule;

class WebsiteTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->rule = new Rule();
    }

    function testNonHttpAddresses()
    {
        $this->assertTrue($this->rule->validate('//google.com'));
    }
}
