<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\DataWrapper\ArrayWrapper;
use Sirius\Validation\Rule\RequiredWithout as Validator;

class RequiredWithoutTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->validator = new Validator();
        $this->validator->setContext(
            new ArrayWrapper(
                array(
                    'item_1' => 'is_present'
                )
            )
        );
    }

    function testValidationWithouItemPresent()
    {
        $this->validator->setOption(Validator::OPTION_ITEM, 'item_2');
        $this->assertTrue($this->validator->validate('abc'));
        $this->assertFalse($this->validator->validate(null));
    }

    function testValidationWithItemPresent()
    {
        $this->validator->setOption(Validator::OPTION_ITEM, 'item_1');
        $this->assertTrue($this->validator->validate('abc'));
        $this->assertTrue($this->validator->validate(null));
    }

}
