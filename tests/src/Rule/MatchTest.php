<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\DataWrapper\ArrayWrapper;
use Sirius\Validation\Rule\Match as Validator;

class MatchTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->validator = new Validator();
        $this->validator->setContext(
            new ArrayWrapper(
                array(
                    'password' => 'secret'
                )
            )
        );
    }

    function testValidationWithItemPresent()
    {
        $this->validator->setOption(Validator::OPTION_ITEM, 'password');
        $this->assertTrue($this->validator->validate('secret'));
        $this->assertFalse($this->validator->validate('abc'));
    }

    function testValidationWithoutItemPresent()
    {
        $this->assertTrue($this->validator->validate('abc'));
        $this->assertTrue($this->validator->validate(null));
    }

}
