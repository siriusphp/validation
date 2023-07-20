<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\Rule\GreaterThan as Rule;

final class GreaterThanTest extends \PHPUnit\Framework\TestCase
{

    protected function setUp(): void
    {
        $this->rule = new Rule();
    }

    function testDefaultOptions(): void
    {
        $this->assertNull($this->rule->getOption('min'));
        $this->assertTrue($this->rule->getOption('inclusive'));
    }

    function testExclusiveValidation(): void
    {
        $this->rule->setOption('inclusive', false);
        $this->rule->setOption('min', 100);
        $this->assertFalse($this->rule->validate(100));
    }

    function testValidationWithoutALimit(): void
    {
        $this->assertTrue($this->rule->validate(0));
    }

    function testConstructCsvFormatMinZeroAndInclusiveFalse(): void
    {
        $this->rule = new Rule('0,false');
        $this->assertSame('0', $this->rule->getOption('min'));
        $this->assertSame(false, $this->rule->getOption('inclusive'));
    }

    function testConstructWithMinValueZeroQueryStringFormat(): void
    {
        $this->rule = new Rule('min=0');
        $this->assertSame('0', $this->rule->getOption('min'));
    }

    function testConstructWithMinValueZeroCsvFormat(): void
    {
        $this->rule = new Rule('0');
        $this->assertSame('0', $this->rule->getOption('min'));
    }
}
