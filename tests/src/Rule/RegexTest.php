<?php

namespace Latinosoft\Validation\Rule;

use Latinosoft\Validation\Rule\Regex as Rule;

class RegexTest extends \PHPUnit\Framework\TestCase
{

    function setUp(): void
    {
        $this->rule = new Rule();
    }

    function testValidationWithoutARegexPattern()
    {
        // pattern was not set, everything is valid
        $this->assertTrue($this->rule->validate('abc'));
    }
}
