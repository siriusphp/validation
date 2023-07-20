<?php

namespace Sirius\Validation\Rule\Upload;

class ImageRatioTest extends \PHPUnit\Framework\TestCase
{

    protected function setUp(): void
    {
        $this->validator = new ImageRatio(array( 'ratio' => 1 ));
    }

    function testMissingFiles(): void
    {
        $fileName = 'file_that_does_not_exist.gif';
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

    function testSquare(): void
    {
        $fileName = 'square_image.gif';
        $file     = array(
            'name'     => $fileName,
            'type'     => 'not_required',
            'size'     => 'not_required',
            'tmp_name' => realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . $fileName,
            'error'    => UPLOAD_ERR_OK
        );
        $this->assertTrue($this->validator->validate($file));
    }

    function testAlmostSquare(): void
    {
        $fileName = 'almost_square_image.gif';
        $file     = array(
            'name'     => $fileName,
            'type'     => 'not_required',
            'size'     => 'not_required',
            'tmp_name' => realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . $fileName,
            'error'    => UPLOAD_ERR_OK
        );
        $this->assertFalse($this->validator->validate($file));

        // change the error margin
        $this->validator->setOption(ImageRatio::OPTION_ERROR_MARGIN, 0.2);
        $this->assertTrue($this->validator->validate($file));
    }

    function testRatioZero(): void
    {
        $this->validator->setOption(ImageRatio::OPTION_RATIO, 0);
        $fileName = 'almost_square_image.gif';
        $file     = array(
            'name'     => $fileName,
            'type'     => 'not_required',
            'size'     => 'not_required',
            'tmp_name' => realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . $fileName,
            'error'    => UPLOAD_ERR_OK
        );
        $this->assertTrue($this->validator->validate($file));
    }

    function testInvalidRatio(): void
    {
        $this->validator->setOption(ImageRatio::OPTION_RATIO, 'abc');
        $fileName = 'almost_square_image.gif';
        $file     = array(
            'name'     => $fileName,
            'type'     => 'not_required',
            'size'     => 'not_required',
            'tmp_name' => realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . $fileName,
            'error'    => UPLOAD_ERR_OK
        );
        $this->assertTrue($this->validator->validate($file));
    }

    function testRatioAsString(): void
    {
        $this->validator->setOption(ImageRatio::OPTION_RATIO, '4:3');
        $fileName = '4_by_3_image.jpg';
        $file     = array(
            'name'     => $fileName,
            'type'     => 'not_required',
            'size'     => 'not_required',
            'tmp_name' => realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . $fileName,
            'error'    => UPLOAD_ERR_OK
        );
        $this->assertTrue($this->validator->validate($file));
    }

    function testFileNotAnImage(): void
    {
        $this->validator->setOption(ImageRatio::OPTION_RATIO, '4:3');
        $fileName = 'corrupt_image.jpg';
        $file     = array(
            'name'     => $fileName,
            'type'     => 'not_required',
            'size'     => 'not_required',
            'tmp_name' => realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . $fileName,
            'error'    => UPLOAD_ERR_OK
        );
        $this->assertFalse($this->validator->validate($file));
    }

}
