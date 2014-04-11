<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\Rule\InList as Validator;

class InListTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->validator = new Validator();
    }

    function testValidationWithoutALIstOfAcceptableValues()
    {
        $this->assertTrue($this->validator->validate('abc'));
    }
}
