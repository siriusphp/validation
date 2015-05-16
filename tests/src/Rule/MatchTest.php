<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\DataWrapper\ArrayWrapper;
use Sirius\Validation\Rule\Match as Rule;

class MatchTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->rule = new Rule();
        $this->rule->setContext(
            new ArrayWrapper(
                array(
                    'password' => 'secret'
                )
            )
        );
    }

    function testValidationWithItemPresent()
    {
        $this->rule->setOption(Rule::OPTION_ITEM, 'password');
        $this->assertTrue($this->rule->validate('secret'));
        $this->assertFalse($this->rule->validate('abc'));
    }

    function testValidationWithoutItemPresent()
    {
        $this->assertTrue($this->rule->validate('abc'));
        $this->assertTrue($this->rule->validate(null));
    }

}
