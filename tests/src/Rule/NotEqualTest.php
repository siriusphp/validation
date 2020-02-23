<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\Rule\NotEqual as Rule;

class NotEqualTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->rule = new Rule();
    }

    function testValidationWithOptionSet()
    {
        $this->rule->setOption(Rule::OPTION_VALUE, '123');
        $this->assertFalse($this->rule->validate('123'));
        $this->assertTrue($this->rule->validate('abc'));
    }

    function testValidationWithoutOptionSet()
    {
        $this->assertFalse($this->rule->validate('abc'));
        $this->assertFalse($this->rule->validate(null));
    }

}
