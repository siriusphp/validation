<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\Rule\NotInList as Validator;

class NotInListTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->validator = new Validator();
    }

    function testValidationWithoutAListOfForbiddenValues()
    {
        $this->assertTrue($this->validator->validate('abc'));
    }
}
