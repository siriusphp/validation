<?php

namespace Latinosoft\Validation;

use PHPUnit\Framework\TestCase;

function fakeValidationFunction()
{
    return false;
}

class FakeObject
{
    function toArray()
    {
        return get_object_vars($this);
    }
}

class ValidatorTest extends TestCase
{

    function setUp(): void
    {
        $this->validator = new Validator(new RuleFactory, new ErrorMessage);
    }

    function testIfMessagesCanBeSetAndCleared()
    {
        $this->assertEquals(0, count($this->validator->getMessages()));

        // add empty message does nothing
        $this->validator->addMessage('field_1');
        $this->assertEquals(0, count($this->validator->getMessages()));

        $this->validator->addMessage('field_1', 'Field is required');
        $this->validator->addMessage('field_2', 'Field should be an email');
        $this->assertEquals(2, count($this->validator->getMessages()));

        $this->validator->clearMessages('field_1');
        $this->assertEquals(1, count($this->validator->getMessages()));
        $this->validator->clearMessages();
        $this->assertEquals(0, count($this->validator->getMessages()));
    }

    function testExceptionThrownWhenTheDataIsNotAnArray()
    {
        $this->expectException('InvalidArgumentException');
        $this->validator->validate('string');
        $this->validator->validate(false);
    }

    function testIfValidateExecutes()
    {
        $this->validator
            ->add('field_1', 'Required', null)
            ->add('field_2', 'Email', null, 'This field should be an email');

        $this->assertFalse(
            $this->validator->validate(
                array(
                    'field_1' => 'exists',
                    'field_2' => 'not'
                )
            )
        );

        $this->validator->validate(
            array(
                'field_1' => 'exists',
                'field_2' => 'me@domain.com'
            )
        );

        // execute the validation again without data
        $this->validator->validate();
        $this->assertEquals(0, count($this->validator->getMessages()));

    }

    function testIfMissingItemsValidateAgainstTheRequiredRule()
    {
        $this->validator->add('item', 'required', null, 'This field is required');
        $this->validator->add('items[subitem]', 'required', null, 'This field is required');
        $this->validator->setData(array());
        $this->validator->validate();
        $this->assertEquals($this->validator->getMessages('item'), array( 'This field is required' ));
        $this->assertEquals($this->validator->getMessages('items[subitem]'), array( 'This field is required' ));
    }

    function testDifferentDataFormats()
    {
        $this->validator->add('email', 'email');

        // test array objects
        $data        = new \ArrayObject(array(), \ArrayObject::ARRAY_AS_PROPS);
        $data->email = 'not_an_email';

        $this->validator->validate($data);
        $this->assertEquals(1, count($this->validator->getMessages('email')));

        // test objects with a 'toArray' method
        $data        = new FakeObject();
        $data->email = 'not_an_email';
        $this->validator->validate($data);
        $this->assertEquals(1, count($this->validator->getMessages('email')));
    }

    function testIfExceptionIsThrownOnInvalidRules()
    {
        $this->expectException('\InvalidArgumentException');
        $this->validator->add('random_string');
    }

    function testAddingMultipleRulesAtOnce()
    {
        $this->validator->add(
            array(
                'item'  => array(
                    'required',
                    array( 'minlength', 'min=4', '{label} should have at least {min} characters', 'Item' )
                ),
                'itema' => array( 'required', 'minLength(min=8)', 'required' ),
                'itemb' => 'required'
            )
        );
        $this->validator->validate(
            array(
                'item'  => 'ab',
                'itema' => 'abc'
            )
        );
        $this->assertEquals(array( 'Item should have at least 4 characters' ), $this->validator->getMessages('item'));
        $this->assertEquals(
            array( 'This input should have at least 8 characters' ),
            $this->validator->getMessages('itema')
        );
        $this->assertEquals(array( 'This field is required' ), $this->validator->getMessages('itemb'));
    }

    function testAddingValidationRulesViaStringsWithoutLabelArg()
    {
        $this->validator
            // mixed rules in 1 string
            ->add('item:Item', 'required | minLength({"min":4})')
            // validator options as a QUERY string
            ->add('itema:Item', 'minLength', 'min=8')
            // validator without options and custom message
            ->add('itemb:Item B', 'required')
            // validator with defaults
            ->add('itemc', 'email');
        $this->validator->validate(array( 'item' => 'ab', 'itema' => 'abc', 'itemc' => 'abc' ));
        $this->assertEquals(array( 'Item should have at least 4 characters' ),
            array( (string) $this->validator->getMessages('item')[0] ));
        $this->assertEquals(array( 'Item should have at least 8 characters' ), $this->validator->getMessages('itema'));
        $this->assertEquals(array( 'Item B is required' ), $this->validator->getMessages('itemb'));
        $this->assertEquals(array( 'This input must be a valid email address' ),
            $this->validator->getMessages('itemc'));
    }

    function testAddingValidationRulesViaStrings()
    {
        $this->validator
            // mixed rules in 1 string
            ->add('item', 'required | minLength({"min":4})({label} should have at least {min} characters)(Item)')
            // validator options as a QUERY string
            ->add('itema', 'minLength', 'min=8', '{label} should have at least {min} characters', 'Item')
            // validator without options and custom message
            ->add('itemb', 'required()(Item B is required)')
            // validator with defaults
            ->add('itemc', 'email');
        $this->validator->validate(array( 'item' => 'ab', 'itema' => 'abc', 'itemc' => 'abc' ));
        $this->assertEquals(array( 'Item should have at least 4 characters' ), $this->validator->getMessages('item'));
        $this->assertEquals(array( 'Item should have at least 8 characters' ), $this->validator->getMessages('itema'));
        $this->assertEquals(array( 'Item B is required' ), $this->validator->getMessages('itemb'));
        $this->assertEquals(array( 'This input must be a valid email address' ),
            $this->validator->getMessages('itemc'));
    }

    function testExceptionOnInvalidValidatorOptions()
    {
        $this->expectException('\InvalidArgumentException');
        $this->validator->add('item', 'required', new \stdClass());
    }

    function fakeValidationMethod($value)
    {
        return false;
    }

    static function fakeStaticValidationMethod($value, $return = false)
    {
        return $return;
    }


    function testCallbackValidators()
    {
        $this->validator->add('function', __NAMESPACE__ . '\fakeValidationFunction');
        $this->validator->add('method', array( $this, 'fakeValidationMethod' ));
        $this->validator->add(
            'staticMethod',
            array( __CLASS__, 'fakeStaticValidationMethod' ),
            array( true )
        ); // this will return true

        $this->validator->validate(
            array(
                'function'     => true,
                'method'       => true,
                'staticMethod' => true,
            )
        );
        $this->assertEquals(2, count($this->validator->getMessages()));
    }

    function testRemovingValidationRules()
    {
        $this->validator->add('item', 'required');
        $this->assertFalse($this->validator->validate(array()));

        $this->validator->remove('item', 'required');
        $this->assertTrue($this->validator->validate(array()));
    }

    function testRemovingAllValidationRules()
    {
        $this->validator->remove('item', true);
        $this->validator->add('item', 'required');
        $this->validator->add('item', 'email');
        $this->validator->setData(array());
        $this->assertFalse($this->validator->validate());

        $this->validator->remove('item', true);
        $rules = $this->validator->getRules();
        $this->assertEquals(count($rules['item']->getRules()), 0);
        $this->assertTrue($this->validator->validate(array()));
    }

    function testMatchingRules()
    {
        $this->validator
            ->add('items[*][key]', 'email', null, 'Key must be an email');
        $this->validator->validate(
            array(
                'items' => array(
                    array( 'key' => 'sss' ),
                    array( 'key' => 'sss' )
                )
            )
        );
        $this->assertEquals(array( 'Key must be an email' ), $this->validator->getMessages('items[0][key]'));
        $this->assertEquals(array( 'Key must be an email' ), $this->validator->getMessages('items[1][key]'));
    }

    function testIfParametersAreSentToValidationMethods()
    {
        $this->validator
            ->add('a', 'email', array( 0, 1 ), 'This should be an email')
            ->add('b', 'email', array( 0, 1, 2 ), 'This should be an email')
            ->add('c', 'email', array( 0, 1, 2, 3 ), 'This should be an email');
        $this->validator->validate(array( 'a' => 'a', 'b' => 'b', 'c' => 'c' ));
        $messages = $this->validator->getMessages();
        foreach (array( 'a', 'b', 'c' ) as $k) {
            $this->assertEquals(1, count($messages[$k]));
        }
    }

    function testIfExceptionIsThrownForInvalidValidationMethods()
    {
        $this->expectException('\InvalidArgumentException');
        $this->validator->add('item', 'faker');
        $this->validator->validate(array( 'item' => true ));
    }

    function testEmptyArrayValidation()
    {
        $this->validator->add(array(
            'a' => array( 'required' ),
            'b' => array( 'required' )
        ));
        $this->validator->validate(array());
        $this->assertEquals(2, count($this->validator->getMessages()));
    }

    function testValidationRequireConditional()
    {
        $this->validator->add(array(
            'a' => array( 'number', 'requiredWith(b)' ),
            'b' => array( 'number', 'requiredWith(a)' )
        ));
        $this->assertTrue($this->validator->validate(array()));
    }
}
