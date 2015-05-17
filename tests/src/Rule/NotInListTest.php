<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\Rule\NotInList as Rule;

class NotInListTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->rule = new Rule();
    }

    function testValidationWithoutAListOfForbiddenValues()
    {
        $this->assertTrue($this->rule->validate('abc'));
    }
}
