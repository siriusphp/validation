<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\Rule\Callback as Rule;

class CallbackTest extends \PHPUnit\Framework\TestCase
{

    protected function setUp(): void
    {
        $this->rule = new Rule();
    }

    function testValidationWithoutAValidCallback(): void
    {
        $this->rule->setOption(Rule::OPTION_CALLBACK, 'ssss');
        $this->assertTrue($this->rule->validate('abc'));
    }

    function testGetUniqueIdForCallbacksAsStrings(): void
    {
        $this->rule->setOption(Rule::OPTION_CALLBACK, 'is_int');
        $this->assertTrue(strpos($this->rule->getUniqueId(), '|is_int') !== false);

        $this->rule->setOption(Rule::OPTION_CALLBACK, 'Class::method');
        $this->assertTrue(strpos($this->rule->getUniqueId(), '|Class::method') !== false);
    }

    function testGetUniqueIdForCallbacksAsArrays(): void
    {
        $this->rule->setOption(Rule::OPTION_CALLBACK, array( 'Class', 'method' ));
        $this->assertTrue(strpos($this->rule->getUniqueId(), '|Class::method') !== false);

        $this->rule->setOption(Rule::OPTION_CALLBACK, array( $this, 'setUp' ));
        $this->assertTrue(strpos($this->rule->getUniqueId(), '->setUp') !== false);
    }

    function testGetUniqueIdForCallbacksWithArguments(): void
    {
        $this->rule->setOption(Rule::OPTION_CALLBACK, 'is_int');
        $this->rule->setOption(Rule::OPTION_ARGUMENTS, array( 'b' => 2, 'a' => 1 ));

        // arguments should be sorted by key so test for that too
        $this->assertTrue(strpos($this->rule->getUniqueId(), '|{"a":1,"b":2}') !== false);
    }

    function testGetUniqueIdForClosures(): void
    {
        $this->rule->setOption(
            Rule::OPTION_CALLBACK,
            function ($value, $valueIdentifier) {
                return true;
            }
        );
        $this->assertNotNull($this->rule->getUniqueId());
    }
}
