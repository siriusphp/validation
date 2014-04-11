<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\Rule\Regex as Validator;

class RegexTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->validator = new Validator();
    }

    function testValidationWithoutARegexPattern()
    {
        // pattern was not set, everything is valid
        $this->assertTrue($this->validator->validate('abc'));
    }
}
