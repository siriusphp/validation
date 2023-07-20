<?php

namespace Sirius\Validation\Rule\Upload;

final class SizeTest extends \PHPUnit\Framework\TestCase
{

    protected function setUp(): void
    {
        $this->validator = new Size(array( 'size' => '1M' ));
    }

    function testMissingFiles(): void
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

    function testNoUpload(): void
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

    function testFile(): void
    {
        $fileName = 'real_jpeg_file.jpg';
        $file     = array(
            'name'     => $fileName,
            'type'     => 'not_required',
            'size'     => 'not_required',
            'tmp_name' => realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . $fileName,
            'error'    => UPLOAD_ERR_OK
        );
        $this->assertTrue($this->validator->validate($file));

        // change size
        $this->validator->setOption(Size::OPTION_SIZE, '10K');
        $this->assertFalse($this->validator->validate($file));
    }

    function testSizeAsNumber(): void
    {
        $fileName = 'real_jpeg_file.jpg';
        $file     = array(
            'name'     => $fileName,
            'type'     => 'not_required',
            'size'     => 'not_required',
            'tmp_name' => realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . $fileName,
            'error'    => UPLOAD_ERR_OK
        );
        $this->validator->setOption(Size::OPTION_SIZE, 1000000000000);
        $this->assertTrue($this->validator->validate($file));

        // change size
        $this->validator->setOption(Size::OPTION_SIZE, 10000);
        $this->assertFalse($this->validator->validate($file));
    }
}
