<?php

namespace Latinosoft\Validation\Rule;

use Latinosoft\Validation\Rule\LessThan as Rule;

class LessThanTest extends \PHPUnit\Framework\TestCase
{

    function setUp(): void
    {
        $this->rule = new Rule();
    }

    function testExclusiveValidation()
    {
        $this->rule->setOption('inclusive', false);
        $this->rule->setOption('max', 100);
        $this->assertFalse($this->rule->validate(100));
    }

    function testValidationWithoutALimit()
    {
        $this->assertTrue($this->rule->validate(0));
    }

    function testOptionNormalizationForHttpQueryString()
    {
        $this->rule = new Rule('max=100&inclusive=false');
        $this->assertFalse($this->rule->validate(100));

        $this->rule = new Rule('max=100&inclusive=true');
        $this->assertTrue($this->rule->validate(100));
    }

    function testOptionNormalizationForJsonString()
    {
        $this->rule = new Rule('{"max": 100, "inclusive": false}');
        $this->assertFalse($this->rule->validate(100));
    }

    function testOptionNormalizationForCsvString()
    {
        $this->rule = new Rule('100,false');
        $this->assertFalse($this->rule->validate(100));

        $this->rule = new Rule('100,true');
        $this->assertTrue($this->rule->validate(100));
    }
}
