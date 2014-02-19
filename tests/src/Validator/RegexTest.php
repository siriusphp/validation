<?php

namespace Sirius\Validation\Validator;

use Sirius\Validation\Validator\Regex as Validator;

class RegexTest extends \PHPUnit_Framework_TestCase  {
    
    function setUp() {
        $this->validator = new Validator();
    }
    
    function testValidationWithoutARegexPattern() {
        // pattern was not set, everything is valid
        $this->assertTrue($this->validator->validate('abc'));
    }
}