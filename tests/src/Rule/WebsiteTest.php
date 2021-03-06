<?php

namespace Sirius\Validation\Rule;

use Sirius\Validation\Rule\Website as Rule;

class WebsiteTest extends \PHPUnit\Framework\TestCase
{

    protected function setUp(): void
    {
        $this->rule = new Rule();
    }

    function testNonHttpAddresses()
    {
        $this->assertTrue($this->rule->validate('//google.com'));
    }
}
