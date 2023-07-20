<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\Rule\NotInList as Rule;

class NotInListTest extends \PHPUnit\Framework\TestCase
{

    protected function setUp(): void
    {
        $this->rule = new Rule();
    }

    function testValidationWithoutAListOfForbiddenValues(): void
    {
        $this->assertTrue($this->rule->validate('abc'));
    }
}
