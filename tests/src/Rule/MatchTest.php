<?php

namespace Latinosoft\Validation\Rule;

use Latinosoft\Validation\DataWrapper\ArrayWrapper;
use Latinosoft\Validation\Rule\Match as Rule;

class MatchTest extends \PHPUnit\Framework\TestCase
{

    function setUp(): void
    {
        $this->rule = new Rule();
        $this->rule->setContext(
            new ArrayWrapper(
                array(
                    'password' => 'secret'
                )
            )
        );
    }

    function testValidationWithItemPresent()
    {
        $this->rule->setOption(Rule::OPTION_ITEM, 'password');
        $this->assertTrue($this->rule->validate('secret'));
        $this->assertFalse($this->rule->validate('abc'));
    }

    function testValidationWithoutItemPresent()
    {
        $this->assertTrue($this->rule->validate('abc'));
        $this->assertTrue($this->rule->validate(null));
    }

}
