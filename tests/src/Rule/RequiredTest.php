<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\DataWrapper\ArrayWrapper;
use Sirius\Validation\Rule\Required as Rule;

class RequiredTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @var Rule
     */
    protected $rule;

    protected function setUp(): void
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
