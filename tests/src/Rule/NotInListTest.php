<?php

namespace Latinosoft\Validation\Rule;

use Latinosoft\Validation\Rule\NotInList as Rule;

class NotInListTest extends \PHPUnit\Framework\TestCase
{

    function setUp(): void
    {
        $this->rule = new Rule();
    }

    function testValidationWithoutAListOfForbiddenValues()
    {
        $this->assertTrue($this->rule->validate('abc'));
    }
}
