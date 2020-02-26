<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\Rule\InList as Rule;

class InListTest extends \PHPUnit\Framework\TestCase
{

    protected function setUp(): void
    {
        $this->rule = new Rule();
    }

    function testValidationWithoutALIstOfAcceptableValues()
    {
        $this->assertTrue($this->rule->validate('abc'));
    }
}
