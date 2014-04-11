<?php

namespace Sirius\Validation;

class ValueValidatorTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->validator = new ValueValidator();
    }

    function testAddingValidationRulesRegularly()
    {
        $this->validator
            ->add('required')
            ->add('minlength', '{"min":4}', '{label} should have at least {min} characters', 'Item');
        $this->validator->validate('ab');
        $this->assertEquals(array('Item should have at least 4 characters'), $this->validator->getMessages());

    }

    function testAddingValidationRulesViaStrings()
    {
        $this->validator
            ->add('required | minlength({"min":4})({label} should have at least {min} characters)(Item)');
        $this->validator->validate('ab');
        $this->assertEquals(array('Item should have at least 4 characters'), $this->validator->getMessages());
    }

    function testRemovingValidationRules()
    {
        $this->validator
            ->add('required');
        $this->assertFalse($this->validator->validate(null));
        $this->validator
            ->remove('required');
        $this->assertTrue($this->validator->validate(null));
    }

    function testRemovingAllRules()
    {
        $this->validator
            ->add('required')
            ->add('minlength', '{"min":4}', '{label} should have at least {min} characters', 'Item');
        $this->validator->validate('ab');
        $this->assertEquals(array('Item should have at least 4 characters'), $this->validator->getMessages());
        $this->validator
            ->remove(true);
        $this->assertTrue($this->validator->validate(null));
    }
}
