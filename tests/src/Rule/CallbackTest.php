<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\Rule\Callback as Validator;

class CallbackTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->validator = new Validator();
    }

    function testValidationWithoutAValidCallback()
    {
        $this->validator->setOption(Validator::OPTION_CALLBACK, 'ssss');
        $this->assertTrue($this->validator->validate('abc'));
    }

    function testGetUniqueIdForCallbacksAsStrings()
    {
        $this->validator->setOption(Validator::OPTION_CALLBACK, 'is_int');
        $this->assertTrue(strpos($this->validator->getUniqueId(), '|is_int') !== false);

        $this->validator->setOption(Validator::OPTION_CALLBACK, 'Class::method');
        $this->assertTrue(strpos($this->validator->getUniqueId(), '|Class::method') !== false);
    }

    function testGetUniqueIdForCallbacksAsArrays()
    {
        $this->validator->setOption(Validator::OPTION_CALLBACK, array('Class', 'method'));
        $this->assertTrue(strpos($this->validator->getUniqueId(), '|Class::method') !== false);

        $this->validator->setOption(Validator::OPTION_CALLBACK, array($this, 'setUp'));
        $this->assertTrue(strpos($this->validator->getUniqueId(), '->setUp') !== false);
    }

    function testGetUniqueIdForCallbacksWithArguments()
    {
        $this->validator->setOption(Validator::OPTION_CALLBACK, 'is_int');
        $this->validator->setOption(Validator::OPTION_ARGUMENTS, array('b' => 2, 'a' => 1));

        // arguments should be sorted by key so test for that too
        $this->assertTrue(strpos($this->validator->getUniqueId(), '|{"a":1,"b":2}') !== false);
    }

    function testGetUniqueIdForClosures()
    {
        $this->validator->setOption(
            Validator::OPTION_CALLBACK,
            function ($value, $valueIdentifier) {
                return true;
            }
        );
        $this->assertNotNull($this->validator->getUniqueId());
    }
}
