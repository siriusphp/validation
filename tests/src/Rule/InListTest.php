<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\Rule\InList as Rule;

class InListTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->rule = new Rule();
    }

    function testValidationWithoutALIstOfAcceptableValues()
    {
        $this->assertTrue($this->rule->validate('abc'));
    }
}
