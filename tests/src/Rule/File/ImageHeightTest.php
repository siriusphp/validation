<?php

namespace Sirius\Validation\Rule\File;

final class ImageHeightTest extends \PHPUnit\Framework\TestCase
{

    protected function setUp(): void
    {
        $this->validator = new ImageHeight(array( 'min' => 400 ));
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

        $file = realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . 'square_image.gif';
        $this->assertFalse($this->validator->validate($file));

        // change minimum
        $this->validator->setOption(ImageHeight::OPTION_MIN, 200);
        $this->assertTrue($this->validator->validate($file));
    }

}
