<?php

namespace Latinosoft\Validation\Rule\Upload;

class ImageTest extends \PHPUnit\Framework\TestCase
{

    function setUp(): void
    {
        $this->validator = new Image();
    }

    function testMissingFiles()
    {
        $fileName = 'file_that_does_not_exist.jpg';
        $file     = array(
            'name'     => $fileName,
            'type'     => 'not_required',
            'size'     => 'not_required',
            'tmp_name' => realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . $fileName,
            'error'    => UPLOAD_ERR_OK
        );
        $this->assertFalse($this->validator->validate($file));
    }

    function testNoUpload()
    {
        $file     = array(
            'name'     => 'not_required',
            'type'     => 'not_required',
            'size'     => 'not_required',
            'tmp_name' => 'not_required',
            'error'    => UPLOAD_ERR_NO_FILE
        );
        $this->assertTrue($this->validator->validate($file));
    }

    function testRealImage()
    {
        $this->validator->setOption(Extension::OPTION_ALLOWED_EXTENSIONS, array( 'jpg' ));
        $fileName = 'real_jpeg_file.jpg';
        $file     = array(
            'name'     => $fileName,
            'type'     => 'not_required',
            'size'     => 'not_required',
            'tmp_name' => realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . $fileName,
            'error'    => UPLOAD_ERR_OK
        );
        $this->assertTrue($this->validator->validate($file));
    }

    function testFakeImage()
    {
        $this->validator->setOption(Extension::OPTION_ALLOWED_EXTENSIONS, array( 'jpg' ));
        $fileName = 'fake_jpeg_file.jpg';
        $file     = array(
            'name'     => $fileName,
            'type'     => 'not_required',
            'size'     => 'not_required',
            'tmp_name' => realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . $fileName,
            'error'    => UPLOAD_ERR_OK
        );
        $this->assertFalse($this->validator->validate($file));
    }

    function testExtensionsAsString()
    {
        $this->validator->setOption(Extension::OPTION_ALLOWED_EXTENSIONS, 'GIF, jpg');
        $fileName = 'real_jpeg_file.jpg';
        $file     = array(
            'name'     => $fileName,
            'type'     => 'not_required',
            'size'     => 'not_required',
            'tmp_name' => realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . $fileName,
            'error'    => UPLOAD_ERR_OK
        );
        $this->assertTrue($this->validator->validate($file));
    }

    function testPotentialMessage()
    {
        $this->validator->setOption(Extension::OPTION_ALLOWED_EXTENSIONS, array( 'jpg', 'png' ));
        $this->assertEquals(
            'The file is not a valid image (only JPG, PNG are allowed)',
            (string) $this->validator->getPotentialMessage()
        );
    }
}
