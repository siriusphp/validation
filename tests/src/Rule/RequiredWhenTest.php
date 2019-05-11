<?php

namespace Latinosoft\Validation\Rule;

use Latinosoft\Validation\DataWrapper\ArrayWrapper;
use Latinosoft\Validation\Rule\RequiredWhen as Rule;

class RequiredWhenTest extends \PHPUnit\Framework\TestCase
{

    function setUp(): void
    {
        $this->rule = new Rule();
    }

    function testValidationWithItemValid()
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

    function testValidationWithItemNotValid()
    {
        $this->rule->setOption(Rule::OPTION_ITEM, 'email');
        $this->rule->setOption(Rule::OPTION_RULE, 'Latinosoft\Validation\Rule\Email');
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

    function testValidationWithoutItem()
    {
        $this->rule->setOption(Rule::OPTION_RULE, 'Latinosoft\Validation\Rule\Email');
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

    function testItemRuleSetAsRuleObject()
    {
        $this->rule->setOption(Rule::OPTION_ITEM, 'email');
        $this->rule->setOption(Rule::OPTION_RULE, new \Latinosoft\Validation\Rule\Email);
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

    function testExceptionThrownOnInvalidItemRule()
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
