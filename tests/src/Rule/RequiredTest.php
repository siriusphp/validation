<?php

namespace Latinosoft\Validation\Rule;

use Latinosoft\Validation\DataWrapper\ArrayWrapper;
use Latinosoft\Validation\Rule\Required as Rule;

class RequiredTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @var Rule
     */
    protected $rule;

    function setUp(): void
    {
        $this->rule = new Rule();
    }

    function testValidationWithNull()
    {
        $this->assertFalse($this->rule->validate(null));
    }

    function testValidationWithEmptyString()
    {
        $this->assertFalse($this->rule->validate(''));
    }

    function testValidationWithWhitespaceString()
    {
        $this->assertTrue($this->rule->validate('  '));
    }
}
