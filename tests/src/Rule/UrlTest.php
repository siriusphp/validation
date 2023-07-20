<?php
/**
 * Created by PhpStorm.
 * User: Rhilip
 * Date: 7/27/2019
 * Time: 11:57 AM
 */

namespace Sirius\Validation\Rule;

use Sirius\Validation\Rule\Url as Rule;

final class UrlTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
        $this->rule = new Rule();
    }

    function testValidation(): void
    {
        $this->assertFalse($this->rule->validate(''));
        $this->assertTrue($this->rule->validate('http://www.google.com'));
    }
}
