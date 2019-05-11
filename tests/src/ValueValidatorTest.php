<?php
namespace Latinosoft\Validation;

use PHPUnit\Framework\TestCase;
use Latinosoft\Validation\Rule\GreaterThan;

class ValueValidatorTest extends TestCase
{
    /**
     * @var ValueValidator
     */
    private $validator;

    function setUp(): void
    {
        $this->validator = new ValueValidator();
    }

    function testAddingValidationRulesRegularly()
    {
        $this->validator->add('required')->add('minlength', '{"min":4}',
            '{label} should have at least {min} characters', 'Item');
        $this->validator->validate('ab');
        $this->assertEquals(array(
            'Item should have at least 4 characters'
        ), $this->validator->getMessages());
    }

    function testAddingValidationRulesViaStrings()
    {
        $this->validator->add('required | minlength({"min":4})({label} should have at least {min} characters)(Item)');
        $this->validator->validate('ab');
        $this->assertEquals(array(
            'Item should have at least 4 characters'
        ), $this->validator->getMessages());
    }

    function testRemovingValidationRules()
    {
        $this->validator->add('required');
        $this->assertFalse($this->validator->validate(null));
        $this->validator->remove('required');
        $this->assertTrue($this->validator->validate(null));
    }

    function testRemovingAllRules()
    {
        $this->validator->add('required')->add('minlength', '{"min":4}',
            '{label} should have at least {min} characters', 'Item');
        $this->validator->validate('ab');
        $this->assertEquals(array(
            'Item should have at least 4 characters'
        ), $this->validator->getMessages());
        $this->validator->remove(true);
        $this->assertTrue($this->validator->validate(null));
    }

    function testNonRequiredRules()
    {
        $this->validator->add('email');
        $this->assertTrue($this->validator->validate(null));
        $this->assertTrue($this->validator->validate(''));
    }

    function testDefaultLabel()
    {
        $this->validator->setLabel('Item');
        $this->validator->add('required')->add('minlength', '{"min":4}',
            '{label} should have at least {min} characters');
        $this->validator->validate('ab');
        $this->assertEquals(array(
            'Item should have at least 4 characters'
        ), $this->validator->getMessages());
    }

    function testParseRuleWithZeroValueInCsvFormat()
    {
        $this->validator->add('GreaterThan(0)');
        /** @var GreaterThan $rule */
        foreach ($this->validator->getRules() as $rule) {
            break;
        }

        $this->assertSame('0', $rule->getOption('min'));

        $this->validator->validate(1);
        $this->assertEmpty($this->validator->getMessages());

        $this->validator->validate(0);
        $this->assertEmpty($this->validator->getMessages());

        $this->validator->validate(-1);
        $this->assertNotEmpty($this->validator->getMessages());
    }
}
