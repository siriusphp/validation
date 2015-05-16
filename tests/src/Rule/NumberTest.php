<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\Rule\Number as Rule;

class NumberTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->rule = new Rule();
    }

    function testValidation()
    {
        $this->assertTrue($this->rule->validate('0'));
        $this->assertTrue($this->rule->validate('0.3'));
        $this->assertFalse($this->rule->validate('0,3'));
    }
}
