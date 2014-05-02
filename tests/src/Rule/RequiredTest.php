<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\DataWrapper\ArrayWrapper;
use Sirius\Validation\Rule\Required as Validator;

class RequiredTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Validator
     */
    protected $validator;

    function setUp()
    {
        $this->validator = new Validator();
    }

    function testValidationWithNull()
    {
        $this->assertFalse($this->validator->validate(null));
    }

    function testValidationWithEmptyString()
    {
        $this->assertFalse($this->validator->validate(''));
    }

    function testValidationWithWhitespaceString()
    {
        $this->assertTrue($this->validator->validate('  '));
    }
}
