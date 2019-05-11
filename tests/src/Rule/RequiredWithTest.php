<?php

namespace Latinosoft\Validation\Rule;

use Latinosoft\Validation\DataWrapper\ArrayWrapper;
use Latinosoft\Validation\Rule\RequiredWith as Rule;

class RequiredWithTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @var Rule
     */
    protected $rule;

    function setUp(): void
    {
        $this->rule = new Rule();
        $this->rule->setContext(
            new ArrayWrapper(
                array(
                    'item_1' => 'is_present'
                )
            )
        );
    }

    function testValidationWithItemPresent()
    {
        $this->rule->setOption(Rule::OPTION_ITEM, 'item_1');
        $this->assertTrue($this->rule->validate('abc'));
        $this->assertFalse($this->rule->validate(null));
        $this->assertFalse($this->rule->validate(''));
    }

    function testValidationWithoutItemPresent()
    {
        $this->rule->setOption(Rule::OPTION_ITEM, 'item_2');
        $this->assertTrue($this->rule->validate('abc'));
        $this->assertTrue($this->rule->validate(null));
    }

    function testValidationWithDeepItems()
    {
        $this->rule->setOption(Rule::OPTION_ITEM, 'lines[*][quantity]');
        $this->rule->setContext(new ArrayWrapper(
                array(
                    'lines' => array(
                        0 => array( 'quantity' => 10, 'price' => 10 ),
                        1 => array( 'quantity' => 20, 'price' => null ),
                    )
                ))
        );
        $this->assertTrue($this->rule->validate(10, 'lines[0][price]'));
        $this->assertFalse($this->rule->validate(null, 'lines[1][price]'));
        $this->assertFalse($this->rule->validate('', 'lines[1][price]'));
    }

}
