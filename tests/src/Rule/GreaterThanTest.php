<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\Rule\GreaterThan as Rule;

class GreaterThanTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->rule = new Rule();
    }

    function testDefaultOptions()
    {
        $this->assertNull($this->rule->getOption('min'));
        $this->assertTrue($this->rule->getOption('inclusive'));
    }

    function testExclusiveValidation()
    {
        $this->rule->setOption('inclusive', false);
        $this->rule->setOption('min', 100);
        $this->assertFalse($this->rule->validate(100));
    }

    function testValidationWithoutALimit()
    {
        $this->assertTrue($this->rule->validate(0));
    }
}
