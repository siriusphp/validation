<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\Rule\Regex as Rule;

class RegexTest extends \PHPUnit\Framework\TestCase
{

    protected function setUp(): void
    {
        $this->rule = new Rule();
    }

    function testValidationWithoutARegexPattern(): void
    {
        // pattern was not set, everything is valid
        $this->assertTrue($this->rule->validate('abc'));
    }
}
