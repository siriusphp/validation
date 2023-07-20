<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\DataWrapper\ArrayWrapper;
use Sirius\Validation\Rule\RequiredWhen as Rule;

class RequiredWhenTest extends \PHPUnit\Framework\TestCase
{

    protected function setUp(): void
    {
        $this->rule = new Rule();
    }

    function testValidationWithItemValid(): void
    {
        $this->rule->setOption(Rule::OPTION_ITEM, 'email');
        $this->rule->setOption(Rule::OPTION_RULE, 'Email');
        $this->rule->setContext(
            new ArrayWrapper(
                array(
                    'email' => 'me@domain.com'
                )
            )
        );
        $this->assertTrue($this->rule->validate('abc'));
        $this->assertFalse($this->rule->validate(null));
        $this->assertFalse($this->rule->validate(''));
    }

    function testValidationWithItemNotValid(): void
    {
        $this->rule->setOption(Rule::OPTION_ITEM, 'email');
        $this->rule->setOption(Rule::OPTION_RULE, 'Sirius\Validation\Rule\Email');
        $this->rule->setContext(
            new ArrayWrapper(
                array(
                    'email' => 'not_a_valid_email'
                )
            )
        );
        $this->assertTrue($this->rule->validate('abc'));
        $this->assertTrue($this->rule->validate(null));
        $this->assertTrue($this->rule->validate(''));
    }

    function testValidationWithoutItem(): void
    {
        $this->rule->setOption(Rule::OPTION_RULE, 'Sirius\Validation\Rule\Email');
        $this->rule->setContext(
            new ArrayWrapper(
                array(
                    'email' => 'not_a_valid_email'
                )
            )
        );
        $this->assertTrue($this->rule->validate('abc'));
        $this->assertTrue($this->rule->validate(null));
        $this->assertTrue($this->rule->validate(''));
    }

    function testItemRuleSetAsRuleObject(): void
    {
        $this->rule->setOption(Rule::OPTION_ITEM, 'email');
        $this->rule->setOption(Rule::OPTION_RULE, new \Sirius\Validation\Rule\Email);
        $this->rule->setContext(
            new ArrayWrapper(
                array(
                    'email' => 'me@domain.com'
                )
            )
        );
        $this->assertTrue($this->rule->validate('abc'));
        $this->assertFalse($this->rule->validate(null));
        $this->assertFalse($this->rule->validate(''));
    }

    function testExceptionThrownOnInvalidItemRule(): void
    {
        $this->expectException('\InvalidArgumentException');
        $this->rule->setOption(Rule::OPTION_ITEM, 'email');
        $this->rule->setOption(Rule::OPTION_RULE, new \stdClass());
        $this->rule->setContext(
            new ArrayWrapper(
                array(
                    'email' => 'me@domain.com'
                )
            )
        );
        $this->assertTrue($this->rule->validate('abc'));
    }
}
