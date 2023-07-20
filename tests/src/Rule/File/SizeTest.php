<?php

namespace Sirius\Validation\Rule\File;

class SizeTest extends \PHPUnit\Framework\TestCase
{

    protected function setUp(): void
    {
        $this->validator = new Size(array( 'size' => '1M' ));
    }

    function testMissingFiles(): void
    {
        $file = realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . 'file_that_does_not_exist.jpg';
        $this->assertFalse($this->validator->validate($file));
    }

    function testFile(): void
    {
        $file = realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . 'real_jpeg_file.jpg';
        $this->assertTrue($this->validator->validate($file));

        // change size
        $this->validator->setOption(Size::OPTION_SIZE, '10K');
        $this->assertFalse($this->validator->validate($file));
    }

    function testSizeAsNumber(): void
    {
        $file = realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . 'real_jpeg_file.jpg';
        $this->validator->setOption(Size::OPTION_SIZE, 1000000000000);
        $this->assertTrue($this->validator->validate($file));

        // change size
        $this->validator->setOption(Size::OPTION_SIZE, 10000);
        $this->assertFalse($this->validator->validate($file));
    }
}
