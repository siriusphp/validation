<?php

namespace Sirius\Validation\Rule;

class FakeValidator extends \Sirius\Validation\Rule\AbstractValidator
{

    function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;
        $this->success = (bool)$value && isset($this->context) && $this->context->getItemValue('key');
        return $this->success;
    }
}


class AbstractValidatorTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->validator = new FakeValidator();
    }

    function testErrorMessagePrototype()
    {
        // we always have an error message prototype
        $this->assertTrue($this->validator->getErrorMessagePrototype() instanceof \Sirius\Validation\ErrorMessage);
        $proto = new \Sirius\Validation\ErrorMessage('Not valid');
        $this->validator->setErrorMessagePrototype($proto);
        $this->assertEquals('Not valid', (string)$this->validator->getErrorMessagePrototype());
    }

    function testDefaultErrorMessageTemplateIsUsed()
    {
        FakeValidator::setDefaultMessageTemplate('Custom default message');
        $this->assertEquals('Custom default message', (string)$this->validator->getPotentialMessage());
    }

    function testMessageIsGeneratedCorrectly()
    {
        $this->validator->setOption('label', 'Accept');
        $this->validator->setMessageTemplate('Field "{label}" must be true, {value} was provided');
        $this->validator->validate('false');
        $this->assertEquals('Field "Accept" must be true, false was provided', (string)$this->validator->getMessage());
    }

    function testNoMessageWhenValidationPasses()
    {
        $this->validator->setContext(array('key' => true));
        $this->assertTrue($this->validator->validate(true));
        $this->assertNull($this->validator->getMessage());
    }

    function testContext()
    {
        $this->assertFalse($this->validator->validate(true));
        $this->validator->setContext(array('key' => true));
        $this->assertTrue($this->validator->validate(true));
    }

    function testErrorMessageTemplateIsUsed()
    {
        $this->validator->setMessageTemplate('Custom message');
        $this->assertEquals('Custom message', (string)$this->validator->getPotentialMessage());
    }

    function testErrorThrownOnInvalidContext()
    {
        $this->setExpectedException('\InvalidArgumentException');
        $this->validator->setContext(new \stdClass());
    }
}
