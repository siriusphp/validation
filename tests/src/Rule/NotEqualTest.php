<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\Rule\NotEqual as Rule;

final class NotEqualTest extends \PHPUnit\Framework\TestCase
{

    protected function setUp(): void
    {
        $this->rule = new Rule();
    }

    function testValidationWithOptionSet(): void
    {
        $this->rule->setOption(Rule::OPTION_VALUE, '123');
        $this->assertFalse($this->rule->validate('123'));
        $this->assertTrue($this->rule->validate('abc'));
    }

    function testValidationWithoutOptionSet(): void
    {
        $this->assertFalse($this->rule->validate('abc'));
        $this->assertFalse($this->rule->validate(null));
    }

}
