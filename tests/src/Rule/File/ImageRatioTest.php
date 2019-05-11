<?php

namespace Latinosoft\Validation\Rule\File;

class ImageRatioTest extends \PHPUnit\Framework\TestCase
{

    function setUp(): void
    {
        $this->validator = new ImageRatio(array( 'ratio' => 1 ));
    }

    function testMissingFiles()
    {
        $file = realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . 'file_that_does_not_exist.jpg';
        $this->assertFalse($this->validator->validate($file));
    }

    function testSquare()
    {
        $file = realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . 'square_image.gif';
        $this->assertTrue($this->validator->validate($file));
    }

    function testAlmostSquare()
    {
        $file = realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . 'almost_square_image.gif';
        $this->assertFalse($this->validator->validate($file));

        // change the error margin
        $this->validator->setOption(ImageRatio::OPTION_ERROR_MARGIN, 0.2);
        $this->assertTrue($this->validator->validate($file));
    }

    function testRatioZero()
    {
        $this->validator->setOption(ImageRatio::OPTION_RATIO, 0);
        $file = realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . 'almost_square_image.gif';
        $this->assertTrue($this->validator->validate($file));
    }

    function testInvalidRatio()
    {
        $this->validator->setOption(ImageRatio::OPTION_RATIO, 'abc');
        $file = realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . 'almost_square_image.gif';
        $this->assertTrue($this->validator->validate($file));
    }

    function testRatioAsString()
    {
        $this->validator->setOption(ImageRatio::OPTION_RATIO, '4:3');
        $file = realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . '4_by_3_image.jpg';
        $this->assertTrue($this->validator->validate($file));
    }

    function testFileNotAnImage()
    {
        $this->validator->setOption(ImageRatio::OPTION_RATIO, '4:3');
        $file = realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . 'corrupt_image.jpg';
        $this->assertFalse($this->validator->validate($file));
    }

}
