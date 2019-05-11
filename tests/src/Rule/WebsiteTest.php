<?php

namespace Latinosoft\Validation\Rule;

use Latinosoft\Validation\Rule\Website as Rule;

class WebsiteTest extends \PHPUnit\Framework\TestCase
{

    function setUp(): void
    {
        $this->rule = new Rule();
    }

    function testNonHttpAddresses()
    {
        $this->assertTrue($this->rule->validate('//google.com'));
    }
}
