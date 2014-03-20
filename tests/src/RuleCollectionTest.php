<?php
namespace Sirius\Validation;

class RuleCollectionTest extends \PHPUnit_Framework_TestCase {
    
    function setUp() {
        $this->collection = new RuleCollection();
    }
    
    function testAddAndRemove() {
        $this->collection->add(new Validator\Required);
        $this->assertEquals(1, count($this->collection));

        $this->collection->remove(new Validator\Required);
        $this->assertEquals(0, count($this->collection));
    }

    function testIterator() {
        $this->collection->add(new Validator\Email);
        $this->collection->add(new Validator\Required);
        
        $rules = array();
        foreach ($this->collection as $k => $rule) {
            $rules[] = $rule;
        }
        $this->assertTrue($rules[0] instanceof Validator\Required);
        $this->assertTrue($rules[1] instanceof Validator\Email);
    }
}