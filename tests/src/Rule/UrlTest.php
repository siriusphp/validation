<?php
/**
 * Created by PhpStorm.
 * User: Rhilip
 * Date: 7/27/2019
 * Time: 11:57 AM
 */

namespace Sirius\Validation\Rule;

use Sirius\Validation\Rule\Url as Rule;

class UrlTest extends \PHPUnit_Framework_TestCase
{
    function setUp()
    {
        $this->rule = new Rule();
    }

    function testValidation()
    {
        $this->assertFalse($this->rule->validate(''));
        $this->assertTrue($this->rule->validate('http://www.google.com'));
    }
}
