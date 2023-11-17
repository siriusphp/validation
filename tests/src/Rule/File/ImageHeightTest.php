<?php

use \Sirius\Validation\Rule\File\ImageHeight;

beforeEach(function () {
    $this->validator = new ImageHeight(array( 'min' => 400 ));
});

test('missing files', function () {
    $file = realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . 'file_that_does_not_exist.jpg';
    expect($this->validator->validate($file))->toBeFalse();
});

test('file', function () {
    $file = realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . 'real_jpeg_file.jpg';
    expect($this->validator->validate($file))->toBeTrue();

    $file = realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . 'square_image.gif';
    expect($this->validator->validate($file))->toBeFalse();

    // change minimum
    $this->validator->setOption(ImageHeight::OPTION_MIN, 200);
    expect($this->validator->validate($file))->toBeTrue();
});
