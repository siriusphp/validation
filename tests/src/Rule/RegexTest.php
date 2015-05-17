<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\Rule\Regex as Rule;

class RegexTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->rule = new Rule();
    }

    function testValidationWithoutARegexPattern()
    {
        // pattern was not set, everything is valid
        $this->assertTrue($this->rule->validate('abc'));
    }
}
