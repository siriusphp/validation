<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\DataWrapper\ArrayWrapper;
use Sirius\Validation\Rule\Required as Rule;

final class RequiredTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @var Rule
     */
    protected $rule;

    protected function setUp(): void
    {
        $this->rule = new Rule();
    }

    function testValidationWithNull(): void
    {
        $this->assertFalse($this->rule->validate(null));
    }

    function testValidationWithEmptyString(): void
    {
        $this->assertFalse($this->rule->validate(''));
    }

    function testValidationWithWhitespaceString(): void
    {
        $this->assertTrue($this->rule->validate('  '));
    }
}
