<?php

namespace Sirius\Validation\Test;

use Sirius\Validation\Helper;
use Sirius\Validation\Validator;

function fakeValidationFunction() {
	return false;
}

class ValidatorTest extends \PHPUnit_Framework_TestCase  {

	function setUp() {
		$this->validator = new Validator();
	}

	function testGlobalMessages() {
		$messages = Validator::getGlobalDefaultMessages();
		$this->assertEquals('Value does not match validation criteria', $messages['_default']);

		Validator::setGlobalDefaultMessages('_default', 'Field is not valid');
		$messages = Validator::getGlobalDefaultMessages();
		$this->assertEquals('Field is not valid', $messages['_default']);

		Validator::setGlobalDefaultMessages(array(
			'_default' => 'Field not valid',
			'required' => 'Field is required'
		));
		$messages = Validator::getGlobalDefaultMessages();
		$this->assertEquals('Field not valid', $messages['_default']);
		$this->assertEquals('Field is required', $messages['required']);
	}

	function testDefaultMessage() {
		// add validation method that always returns false so we check 
		// the validation message
		Helper::addMethod('fake_method', function() { return false; });
		$this->validator->add('item', 'fake_method');
		$this->validator->validate(array('item' => 'does not matter'));
		$messages = Validator::getGlobalDefaultMessages();
		$this->assertEquals(array($messages['_default']), $this->validator->getMessages('item'));
	}

	function testIfRulesAreSetViaTheContructor() {
		$this->validator = new Validator(array(
			array('item_1', 'required'),
			array('item_2', 'required'),
		));
		$this->assertFalse($this->validator->validate(array()));
		$this->assertEquals(2, count($this->validator->getMessages()));
	}

	function testIfMessagesCanBeSetAndCleared() {
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

	function testMessageCompilation() {
		$this->validator
			->add('item', 'required', null, array('%s is required', 'Item'));
		$this->validator->validate(array());
		$this->assertEquals(array('Item is required'), $this->validator->getMessages('item'));
	}

	function testIfSetDataThrowsExceptionWhenTheDataIsNotAnArray() {
		$this->setExpectedException('InvalidArgumentException');
		$this->validator->setData('string');
		$this->validator->setData(false);
	}

	function testIfAddConditionThrowsExceptionWhenTheConditionIsNotACallback() {
		$this->setExpectedException('InvalidArgumentException');
		$this->validator->addCondition('condition_name', 'string_which_is_not_a_function');		
	}

	function testRemovingConditions() {

		$this->validator
			->addCondition('precondition_does_not_verify', function($data) {
				return false;
			})
			->add('item', 'required', null, 'Field should be an email because of the precondition', 'precondition_does_not_verify');

		$this->assertTrue($this->validator->validate(array()));

		$this->validator->removeCondition('precondition_does_not_verify');

		$this->assertFalse($this->validator->validate(array()));
	}

	function testIfValidateExecutes() {
		$this->validator
			->add('field_1', 'required', null)
			->add('field_2', 'email', null, 'This field should be an email');

		$this->assertFalse($this->validator->validate(array(
			'field_1' => 'exists',
			'field_2' => 'shit'
		)));

		$this->validator->validate(array(
			'field_1' => 'exists',
			'field_2' => 'me@domain.com'
		));
		$this->assertEquals(0, count($this->validator->getMessages()));

	}

	function testIfMissingItemsValidateAgainstTheRequiredRule() {
		$this->validator->add('item', 'required', null, 'This field is required');
		$this->validator->add('items[subitem]', 'required', null, 'This field is required');
		$this->validator->setData(array());
		$this->validator->validate();
		$this->assertEquals($this->validator->getMessages('item'), array('This field is required'));
		$this->assertEquals($this->validator->getMessages('items[subitem]'), array('This field is required'));
	}

	function testAddingValidationRulesViaStrings() {
		$this->validator->addCondition('condition', function() { return true; });
		$this->validator
			->add('item', 'email[]Item should be an email | minLength[3]Item should have at least 3 characters')
			->add('itemb', 'if(condition)required[]Item B is required')
			->add('itemc', 'if(condition)email');
		$this->validator->validate(array('item' => 'ab', 'itemc' => 'abc'));
		$this->assertEquals(array(
			'Item should be an email',
			'Item should have at least 3 characters',
		), $this->validator->getMessages('item'));
		$this->assertEquals(array('Item B is required'), $this->validator->getMessages('itemb'));
		$this->assertEquals(array('Value is not a valid email address'), $this->validator->getMessages('itemc'));
	}

	function testIfConditionIsCheckedBeforeValidating() {
		// one field
		$this->validator
			->add('special_reason', 'required', null, 'Please enter the special reason you chose us.', 'other_reason_is_checked')
			->addCondition('other_reason_is_checked', function($data) {
				return array_key_exists('other_reason', $data) and $data['other_reason'];
			});
		$this->validator->setData(array(
			'special_reason' => null,
			'other_reason' => true,
		));
		$this->validator->validate();
		$this->assertFalse($this->validator->validate(), 'Validator checked if condition was met');
		$this->assertEquals($this->validator->getMessages('special_reason'), array('Please enter the special reason you chose us.'));

		// other reason is not checked
		$this->validator->setData(array());
		$this->assertTrue($this->validator->validate(), 'Validator did not validate when the condition was not met');
	}

	function fakeValidationMethod() {
		return false;
	}

	static function fakeStaticValidationMethod() {
		return false;
	}

	function testGeneratingTheRuleIdAllowsMultipleRegexRules() {
		// regexes
		$this->validator->add('item', 'regex', array('/abc/'), 'Value does not match 1st rule');
		$this->validator->add('item', 'regex', array('/bcd/'), 'Value does not match 2nd rule');
		// callbacks
		$this->validator->add('item', 'callback', array(array($this, 'fakeValidationMethod')), 'Value does not match 3rd rule'); 
		$this->validator->add('item', 'callback', array(array(__CLASS__, 'fakeStaticValidationMethod')), 'Value does not match 4th rule'); 
		$this->validator->add('item', 'callback', array(function() { return false; }), 'Value does not match 5th rule'); 
		$this->validator->add('item', 'callback', array(__NAMESPACE__.'\fakeValidationFunction'), 'Value does not match 6th rule'); 
		$this->validator->validate(array('item' => '123'));
		$this->assertEquals(array(
			'Value does not match 1st rule',
			'Value does not match 2nd rule',
			'Value does not match 3rd rule',
			'Value does not match 4th rule',
			'Value does not match 5th rule',
			'Value does not match 6th rule',
		), $this->validator->getMessages('item'));

	}

	function testRemovingValidationRules() {
		$this->validator->add('item', 'required');
		$this->assertFalse($this->validator->validate(array()));

		$this->validator->remove('item', 'required');
		$this->assertTrue($this->validator->validate(array()));
	}

	function testRemovingAllValidationRules() {
		$this->validator->remove('item', true);
		$this->validator->add('item', 'required');
		$this->validator->add('item', 'email');
		$this->assertFalse($this->validator->validate(array()));

		$this->validator->remove('item', true);
		$this->assertTrue($this->validator->validate(array()));
	}

	function testMatchingRules() {
		$this->validator
			->add('items[*][key]', 'email', null, 'Key must be an email');
		$this->validator->validate(array(
			'items' => array(
				array('key' => 'sss'),
				array('key' => 'sss')
			)
		));
		#var_dump($this->validator->getMessages());
		$this->assertEquals(array('Key must be an email'), $this->validator->getMessages('items[0][key]'));
		$this->assertEquals(array('Key must be an email'), $this->validator->getMessages('items[1][key]'));
	}

	function testIfParametersAreSentToValidationMethods() {
		$this->validator
			->add('a', 'email', array(0, 1), 'This should be an email')
			->add('b', 'email', array(0, 1, 2), 'This should be an email')
			->add('c', 'email', array(0, 1, 2, 3), 'This should be an email');
		$this->validator->validate(array('a' => 'a', 'b' => 'b', 'c' => 'c'));
		$messages = $this->validator->getMessages();
		foreach (array('a', 'b', 'c') as $k) {
			$this->assertEquals(1, count($messages[$k]));
		}
	}

	function testIfExceptionIsThrownForInvalidValidationMethods() {
		$this->setExpectedException('\InvalidArgumentException');
		$this->validator->add('item', 'faker');
		$this->validator->validate(array('item' => true));
	}

}