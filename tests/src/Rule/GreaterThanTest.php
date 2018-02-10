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

    function testConstructCsvFormatMinZeroAndInclusiveFalse()
    {
        $this->rule = new Rule('0,false');
        $this->assertSame('0', $this->rule->getOption('min'));
        $this->assertSame(false, $this->rule->getOption('inclusive'));
    }

    function testConstructWithMinValueZeroQueryStringFormat()
    {
        $this->rule = new Rule('min=0');
        $this->assertSame('0', $this->rule->getOption('min'));
    }

    function testConstructWithMinValueZeroCsvFormat()
    {
        $this->rule = new Rule('0');
        $this->assertSame('0', $this->rule->getOption('min'));
    }
}
