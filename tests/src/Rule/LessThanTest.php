<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\Rule\LessThan as Rule;

final class LessThanTest extends \PHPUnit\Framework\TestCase
{

    protected function setUp(): void
    {
        $this->rule = new Rule();
    }

    function testExclusiveValidation(): void
    {
        $this->rule->setOption('inclusive', false);
        $this->rule->setOption('max', 100);
        $this->assertFalse($this->rule->validate(100));
    }

    function testValidationWithoutALimit(): void
    {
        $this->assertTrue($this->rule->validate(0));
    }

    function testOptionNormalizationForHttpQueryString(): void
    {
        $this->rule = new Rule('max=100&inclusive=false');
        $this->assertFalse($this->rule->validate(100));

        $this->rule = new Rule('max=100&inclusive=true');
        $this->assertTrue($this->rule->validate(100));
    }

    function testOptionNormalizationForJsonString(): void
    {
        $this->rule = new Rule('{"max": 100, "inclusive": false}');
        $this->assertFalse($this->rule->validate(100));
    }

    function testOptionNormalizationForCsvString(): void
    {
        $this->rule = new Rule('100,false');
        $this->assertFalse($this->rule->validate(100));

        $this->rule = new Rule('100,true');
        $this->assertTrue($this->rule->validate(100));
    }
}
