<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\DataWrapper\ArrayWrapper;
use Sirius\Validation\Rule\Matching as Rule;

class MatchTest extends \PHPUnit\Framework\TestCase
{

    protected function setUp(): void
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

    function testValidationWithItemPresent(): void
    {
        $this->rule->setOption(Rule::OPTION_ITEM, 'password');
        $this->assertTrue($this->rule->validate('secret'));
        $this->assertFalse($this->rule->validate('abc'));
    }

    function testValidationWithoutItemPresent(): void
    {
        $this->assertTrue($this->rule->validate('abc'));
        $this->assertTrue($this->rule->validate(null));
    }

}
