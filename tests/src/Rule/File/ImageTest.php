<?php

namespace Latinosoft\Validation\Rule\File;

class ImageTest extends \PHPUnit\Framework\TestCase
{

    function setUp(): void
    {
        $this->validator = new Image();
    }

    function testMissingFiles()
    {
        $file = realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . 'file_that_does_not_exist.jpg';
        $this->assertFalse($this->validator->validate($file));
    }

    function testRealImage()
    {
        $this->validator->setOption(Extension::OPTION_ALLOWED_EXTENSIONS, array( 'jpg' ));
        $file = realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . 'real_jpeg_file.jpg';
        $this->assertTrue($this->validator->validate($file));
    }

    function testFakeImage()
    {
        $this->validator->setOption(Extension::OPTION_ALLOWED_EXTENSIONS, array( 'jpg' ));
        $file = realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . 'fake_jpeg_file.jpg';
        $this->assertFalse($this->validator->validate($file));
    }

    function testExtensionsAsString()
    {
        $this->validator->setOption(Extension::OPTION_ALLOWED_EXTENSIONS, 'GIF, jpg');
        $file = realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . 'real_jpeg_file.jpg';
        $this->assertTrue($this->validator->validate($file));
    }

    function testPotentialMessage()
    {
        $this->validator->setOption(Extension::OPTION_ALLOWED_EXTENSIONS, array( 'jpg', 'png' ));
        $this->validator->validate('no_file.jpg');
        $this->assertEquals(
            'The file is not a valid image (only JPG, PNG are allowed)',
            (string) $this->validator->getPotentialMessage()
        );
    }
}
