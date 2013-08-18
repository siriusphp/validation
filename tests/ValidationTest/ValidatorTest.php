<?php

namespace Sirius\Validation\Test;

use Sirius\Validation\Helper;
use Sirius\Validation\Validator;

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

	function testIfSetDataThrowsException() {
		$this->setExpectedException('InvalidArgumentException');
		$this->validator->setData('string');
		$this->validator->setData(false);
	}

	function testIfAddConditionThrowsException() {
		$this->setExpectedException('InvalidArgumentException');
		$this->validator->addCondition('condition_name', 'string_which_is_not_a_function');		
	}

	function testIfMessagesCanBeSet() {
		$this->validator->addMessage('key', 'You must select at least one option');
		$this->validator->addMessage('key[subkey]', 'This field is required');
		$messages = $this->validator->getMessages();
		$this->assertEquals($messages['key'][0], 'You must select at least one option');
		$this->assertEquals($messages['key[subkey]'][0], 'This field is required');
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

}