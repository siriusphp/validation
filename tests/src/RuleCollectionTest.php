<?php
namespace Latinosoft\Validation;

use PHPUnit\Framework\TestCase;

class RuleCollectionTest extends TestCase
{

    function setUp(): void
    {
        $this->collection = new RuleCollection();
    }

    function testAddAndRemove()
    {
        $this->collection->attach(new Rule\Required);
        $this->assertEquals(1, count($this->collection));

        $this->collection->detach(new Rule\Required);
        $this->assertEquals(0, count($this->collection));
    }

    function testIterator()
    {
        $this->collection->attach(new Rule\Email);
        $this->collection->attach(new Rule\Required);

        $rules = array();
        foreach ($this->collection as $k => $rule) {
            $rules[] = $rule;
        }

        // the required rule should be first
        $this->assertTrue($rules[0] instanceof Rule\Required);
        $this->assertTrue($rules[1] instanceof Rule\Email);
    }
}
