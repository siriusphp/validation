<?php

namespace Sirius\Validation\Rule\File;

class ExtensionTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->validator = new Extension();
    }

    function testExistingFiles()
    {
        $this->validator->setOption(Extension::OPTION_ALLOWED_EXTENSIONS, array('jpg'));
        $file = realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . 'real_jpeg_file.jpg';
        $this->assertTrue($this->validator->validate($file));
    }

    function testMissingFiles()
    {
        $this->validator->setOption(Extension::OPTION_ALLOWED_EXTENSIONS, array('jpg'));
        $file = realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . 'file_that_does_not_exist.jpg';
        $this->assertFalse($this->validator->validate($file));
    }

    function testSetOptionAsString()
    {
        $this->validator->setOption(Extension::OPTION_ALLOWED_EXTENSIONS, 'jpg, GIF');
        $file = realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . 'real_jpeg_file.jpg';
        $this->assertTrue($this->validator->validate($file));
    }

    function testPotentialMessage()
    {
        $this->validator->setOption(Extension::OPTION_ALLOWED_EXTENSIONS, array('jpg', 'png'));
        $this->validator->validate('no_file.jpg');
        $this->assertEquals(
            'File does not have an acceptable extension (JPG, PNG)',
            (string)$this->validator->getPotentialMessage()
        );
    }
}
