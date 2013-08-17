<?php

namespace Sirius\Validation\Test;

use Sirius\Validation\Helper as ValidationHelper;

class HelperTest extends \PHPUnit_Framework_TestCase  {

	function testOfRequired(){
		$pool = array(
			'abc' => true,
			1.2 => true,
			0 => false,
		);
		foreach ($pool as $key => $value) {
			$this->assertSame(ValidationHelper::required($key), $value);
		}
		$this->assertSame(ValidationHelper::required(null), false);
	}

	function testOfTruthy() {
		$pool = array(
			'abc' => true,
			1.2 => true,
			0 => false,
			'' => false,
		);
		foreach ($pool as $key => $value) {
			$this->assertSame(ValidationHelper::truthy($key), $value);
		}
	}
	
	function testOfFalsy() {
		$pool = array(
			'abc' => false,
			1.2 => false,
			0 => true,
			'' => true,
		);
		foreach ($pool as $key => $value) {
			$this->assertSame(ValidationHelper::falsy($key), $value);
		}
	}
	function testOfEmail() {
		$pool = array(
			'-fa/lse@gmail.com' => false,
			'12345@hotmail.com' => true,
			'xxx.yyyy-zzz@domain.com.noc' => true,
			'weird.name-99-@yahoo.com' => true
		);
		foreach ($pool as $key => $value) {
			if ($value) {
				$message = $key . ' is a valid email';
			} else {
				$message = $key . ' is NOT a valid email';
			}
			$this->assertSame(ValidationHelper::email($key), $value, $message);
		}
	}
	
	function testOfNumber() {
		$pool = array(
			'1' => true,
			'1,5' => false,
			'2.5' => true,
			'abc' => false
		);
		foreach ($pool as $key => $value) {
			if ($value) {
				$message = $key . ' is a valid number';
			} else {
				$message = $key . ' is NOT a valid number';
			}
			$this->assertSame(ValidationHelper::number($key), $value, $message);
		}
	}

	function testOfInteger() {
		$pool = array(
			'1' => true,
			'12345' => true,
			'1.00' => true,
			'1.24' => false
		);
		foreach ($pool as $key => $value) {
			if ($value) {
				$message = $key . ' is a valid integer';
			} else {
				$message = $key . ' is NOT a valid integer';
			}
			$this->assertSame(ValidationHelper::integer($key), $value, $message);
		}	
	}
	function testOfLessThan() {
		$pool = array(
			array(1, 0.5, false),
			array(1, 1.2, true),
		);
		foreach ($pool as $sample) {
			if ($sample[2]) {
				$message = $sample[0] . ' is less than ' . $sample[1];
			} else {
				$message = $sample[0] . ' is NOT less than ' . $sample[1];
			}
			$this->assertSame(ValidationHelper::lessThan($sample[0], $sample[1]), $sample[2], $message);
		}
	}
	function testOfGreaterThan() {
		$pool = array(
			array(1, 0.5, true),
			array(1, 1.2, false),
		);
		foreach ($pool as $sample) {
			if ($sample[2]) {
				$message = $sample[0] . ' is less than ' . $sample[1];
			} else {
				$message = $sample[0] . ' is NOT less than ' . $sample[1];
			}
			$this->assertSame(ValidationHelper::greaterThan($sample[0], $sample[1]), $sample[2], $message);
		}
	}
	function testOfBetween() {
		$pool = array(
			array(1, 0.5, 0.8, false),
			array(1, 0.9, 1.2, true),
		);
		foreach ($pool as $sample) {
			if ($sample[2]) {
				$message = $sample[0] . ' is between ' . $sample[1] . ' and ' . $sample[2];
			} else {
				$message = $sample[0] . ' is NOT between ' . $sample[1] . ' and ' . $sample[2];
			}
			$this->assertSame(ValidationHelper::between($sample[0], $sample[1], $sample[2]), $sample[3], $message);
		}
	}
	function testOfExactly() {
		$pool = array(
			array(1, '1', true),
			array(1, 1.0, true),
			array(1, 01, true),
			array(1, 'a', false),
		);
		foreach ($pool as $sample) {
			if ($sample[2]) {
				$message = $sample[0] . ' is exactly ' . $sample[1];
			} else {
				$message = $sample[0] . ' is NOT exactly ' . $sample[1];
			}
			$this->assertSame(ValidationHelper::exactly($sample[0], $sample[1]), $sample[2], $message);
		}
	}
	function testOfNot() {
		$pool = array(
			array(1, '1', false),
			array(1, 1.0, false),
			array(1, 01, false),
			array(1, 'a', true),
		);
		foreach ($pool as $sample) {
			if ($sample[2]) {
				$message = $sample[0] . ' is not ' . $sample[1];
			} else {
				$message = $sample[0] . ' is ' . $sample[1];
			}
			$this->assertSame(ValidationHelper::not($sample[0], $sample[1]), $sample[2], $message);
		}
	}
	function testOfAlpha() {
		$pool = array(
			'Some Random String ' => true,
			'123' => false,
			'With other chars :' => false
		);
		foreach ($pool as $key => $value) {
			if ($value) {
				$message = $key . ' is a alphabetic';
			} else {
				$message = $key . ' is NOT a alphabetic';
			}
			$this->assertSame(ValidationHelper::alpha($key), $value, $message);
		}
	}
	function testOfAlphanumeric() {
		$pool = array(
			'Some Random String ' => true,
			'Letters and 123' => true,
			'With other chars :' => false
		);
		foreach ($pool as $key => $value) {
			if ($value) {
				$message = $key . ' is a alphanumeric';
			} else {
				$message = $key . ' is NOT a alphanumeric';
			}
			$this->assertSame(ValidationHelper::alphanumeric($key), $value, $message);
		}
	}
	function testOfAlphanumhyphen() {
		$pool = array(
			'Some Random String ' => true,
			'Letters and 123' => true,
			'With other hyphens _ -' => true,
			'? - this is not acceptable' => false
		);
		foreach ($pool as $key => $value) {
			if ($value) {
				$message = $key . ' is a alphanumhyphen';
			} else {
				$message = $key . ' is NOT a alphanumhyphen';
			}
			$this->assertSame(ValidationHelper::alphanumhyphen($key), $value, $message);
		}
	}
	function testOfMinLength() {
		$pool = array(
			array('abcde', 7, false),
			array('abcde', 4, true),
		);
		foreach ($pool as $sample) {
			if ($sample[2]) {
				$message = $sample[0] . ' has more than ' . $sample[1] . ' characters';
			} else {
				$message = $sample[0] . ' does NOT have more than ' . $sample[1] . ' characters';
			}
			$this->assertSame(ValidationHelper::minLength($sample[0], $sample[1]), $sample[2], $message);
		}
	}
	function testOfMaxLength() {
		$pool = array(
			array('abcde', 4, false),
			array('abcde', 6, true),
		);
		foreach ($pool as $sample) {
			if ($sample[2]) {
				$message = $sample[0] . ' has less than ' . $sample[1] . ' characters';
			} else {
				$message = $sample[0] . ' does NOT have less than ' . $sample[1] . ' characters';
			}
			$this->assertSame(ValidationHelper::maxLength($sample[0], $sample[1]), $sample[2], $message);
		}
	}
	function testOfIn() {
		$pool = array(
			array('6', array('5', '8'), false),
			array('5', array('5', '8'), true),
		);
		foreach ($pool as $sample) {
			$this->assertSame(ValidationHelper::in($sample[0], $sample[1]), $sample[2]);
		}
	}
	function testOfNotIn() {
		$pool = array(
			array('5', array('5', '8'), false),
			array('6', array('5', '8'), true),
		);
		foreach ($pool as $sample) {
			$this->assertSame(ValidationHelper::notIn($sample[0], $sample[1]), $sample[2]);
		}
	}
	function testOfRegex() {
		$pool = array(
			array('abc', '/[0-9]+/', false),
			array('123', '/[0-9]+/', true),
		);
		foreach ($pool as $sample) {
			$this->assertSame(ValidationHelper::regex($sample[0], $sample[1]), $sample[2]);
		}
	}
	function testOfNotRegex() {
		$pool = array(
			array('abc', '/[0-9]+/', true),
			array('123', '/[0-9]+/', false),
		);
		foreach ($pool as $sample) {
			$this->assertSame(ValidationHelper::notRegex($sample[0], $sample[1]), $sample[2]);
		}
	}
	function testOfEqualTo() {
		$pool = array(
			array('value', 'element_1', true),
			array('value', 'element_2', false),
			array('new value', 'element_3[sub_element_1][sub_element_2]', true),
		);
		$context = array(
			'element_1' => 'value',
			'element_2' => 'another_value',
			'element_3' => array (
				'sub_element_1' => array(
					'sub_element_2' => 'new value'
				)
			)
		);
		foreach ($pool as $sample) {
			$this->assertSame(ValidationHelper::equalTo($sample[0], $sample[1], $context), $sample[2], sprintf("%s %s", $sample[0], $sample[1]));
		}
	}

	function testOfWebsite() {
		$pool = array(
			array('https://www.domain.co.uk/', true),
			array('https://www.domain.co.uk/folder/page.html?var=value#!fragment', true),
			array('123', false),
		);
		foreach ($pool as $sample) {
			$this->assertSame(ValidationHelper::website($sample[0]), $sample[1]);
		}
	}

	function testOfUrl() {
		$pool = array(
			array('ftp://ftp.domain.co.uk/', true),
			array('ftp://username:password@domain.co.uk/folder/', true),
			array('123', false),
		);
		foreach ($pool as $sample) {
			$this->assertSame(ValidationHelper::url($sample[0]), $sample[1]);
		}
	}

	function testOfIp() {
		$pool = array(
			array('196.168.100.1', true),
			array('256.5765.53.21', false),
			array('2001:db8:85a3:8d3:1319:8a2e:370:7348', true), //IPv6
		);
		foreach ($pool as $sample) {
			$this->assertSame(ValidationHelper::ip($sample[0]), $sample[1]);
		}
	}

	function testOfSetMaxSize() {
		$set = array(
			'element_1' => 'value',
			'element_2' => 'another_value',
			'element_3' => array (
				'sub_element_1' => 'new value'
			)
		);
		$pool = array(
			array('4', true),
			array('2', false),
		);
		foreach ($pool as $sample) {
			$this->assertSame(ValidationHelper::setMaxSize($set, $sample[0]), $sample[1]);
		}
	}

	function testOfSetMinSize() {
		$set = array(
			'element_1' => 'value',
			'element_2' => 'another_value',
			'element_3' => array (
				'sub_element_1' => 'new value'
			)
		);
		$pool = array(
			array('4', false),
			array('2', true),
		);
		foreach ($pool as $sample) {
			$this->assertSame(ValidationHelper::setMinSize($set, $sample[0]), $sample[1]);
		}
	}

	function testOfSetSize() {
		$set = array(
			'element_1' => 'value',
			'element_2' => 'another_value',
			'element_3' => array (
				'sub_element_1' => 'new value'
			)
		);
		$pool = array(
			array(2, 5, true),
			array(6, 8, false),
		);
		foreach ($pool as $sample) {
			$this->assertSame(ValidationHelper::setSize($set, $sample[0], $sample[1]), $sample[2]);
		}
	}

	function validationCallback($value, $options = false, $context = null) {
		return ($value == 5 and $options = 3 and $context == null);
	}

	function testOfValidAddMethodCalls() {
		ValidationHelper::addMethod('myValidation', array($this, 'validationCallback'));
		$this->assertTrue(ValidationHelper::myValidation(5, 3));
		$this->assertFalse(ValidationHelper::myValidation(5, 3, 3));
	}

	function testOfInvalidAddMethodCalls() {
		$this->setExpectedException('InvalidArgumentException');
		ValidationHelper::addMethod('mySecondValidation', 'nonexistantcallback');
		$this->assertTrue(ValidationHelper::mySecondValidation(5));
	}

/*	function testOfDate() {
		
	}
	function testOfDateTime() {
		
	}
	function testOfTime() {
		
	}
	function testOfCreditCard() {
		
	}
*/

}