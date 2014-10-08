<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\Rule\Between as Validator;

class BetweenTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->validator = new Validator();
    }

    function testValidation()
    {
        $this->validator->setOption('min', 50);
        $this->validator->setOption('max', 100);
        $this->assertFalse($this->validator->validate(40));
        $this->assertFalse($this->validator->validate(110));
        $this->assertTrue($this->validator->validate(80));
    }

}
