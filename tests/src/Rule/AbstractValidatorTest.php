<?php

namespace Sirius\Validation\Rule;

class FakeRule extends \Sirius\Validation\Rule\AbstractRule
{

    function validate($value, string $valueIdentifier = null):bool
    {
        $this->value   = $value;
        $this->success = (bool) $value && isset($this->context) && $this->context->getItemValue('key');

        return $this->success;
    }
}


class AbstractRuleTest extends \PHPUnit\Framework\TestCase
{

    protected function setUp(): void
    {
        $this->rule = new FakeRule();
    }

    function testErrorMessagePrototype(): void
    {
        // we always have an error message prototype
        $this->assertTrue($this->rule->getErrorMessagePrototype() instanceof \Sirius\Validation\ErrorMessage);
        $proto = new \Sirius\Validation\ErrorMessage('Not valid');
        $this->rule->setErrorMessagePrototype($proto);
        $this->assertEquals('Not valid', (string) $this->rule->getErrorMessagePrototype());
    }

    function testMessageIsGeneratedCorrectly(): void
    {
        $this->rule->setOption('label', 'Accept');
        $this->rule->setMessageTemplate('Field "{label}" must be true, {value} was provided');
        $this->rule->validate('false');
        $this->assertEquals('Field "Accept" must be true, false was provided', (string) $this->rule->getMessage());
    }

    function testNoMessageWhenValidationPasses(): void
    {
        $this->rule->setContext(array( 'key' => true ));
        $this->assertTrue($this->rule->validate(true));
        $this->assertNull($this->rule->getMessage());
    }

    function testContext(): void
    {
        $this->assertFalse($this->rule->validate(true));
        $this->rule->setContext(array( 'key' => true ));
        $this->assertTrue($this->rule->validate(true));
    }

    function testErrorMessageTemplateIsUsed(): void
    {
        $this->rule->setMessageTemplate('Custom message');
        $this->assertEquals('Custom message', (string) $this->rule->getPotentialMessage());
    }

    function testErrorThrownOnInvalidContext(): void
    {
        $this->expectException('\InvalidArgumentException');
        $this->rule->setContext(new \stdClass());
    }

    function testGetOption(): void
    {
        $this->rule->setOption('label', 'Accept');
        $this->assertEquals('Accept', $this->rule->getOption('label'));
        $this->assertNull($this->rule->getOption('notExist'));
    }
}
