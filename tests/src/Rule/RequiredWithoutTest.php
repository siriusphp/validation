<?php

namespace Latinosoft\Validation\Rule;

use Latinosoft\Validation\DataWrapper\ArrayWrapper;
use Latinosoft\Validation\Rule\RequiredWithout as Rule;

class RequiredWithoutTest extends \PHPUnit\Framework\TestCase
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

    function testValidationWithoutItemPresent()
    {
        $this->rule->setOption(Rule::OPTION_ITEM, 'item_2');
        $this->assertTrue($this->rule->validate('abc'));
        $this->assertFalse($this->rule->validate(null));
        $this->assertFalse($this->rule->validate(''));
    }

    function testValidationWithItemPresent()
    {
        $this->rule->setOption(Rule::OPTION_ITEM, 'item_1');
        $this->assertTrue($this->rule->validate('abc'));
        $this->assertTrue($this->rule->validate(null));
        $this->assertTrue($this->rule->validate(''));
    }

    function testValidationWithDeepItems()
    {
        $this->rule->setOption(Rule::OPTION_ITEM, 'lines[*][quantity]');
        $this->rule->setContext(new ArrayWrapper(
                array(
                    'lines' => array(
                        0 => array( 'quantity' => null, 'price' => null ),
                        1 => array( 'quantity' => 20, 'price' => null ),
                    )
                ))
        );
        $this->assertFalse($this->rule->validate(null, 'lines[0][price]'));
        $this->assertTrue($this->rule->validate(null, 'lines[1][price]'));
        $this->assertTrue($this->rule->validate('', 'lines[1][price]'));
    }
}
