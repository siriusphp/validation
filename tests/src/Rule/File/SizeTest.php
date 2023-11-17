<?php

use \Sirius\Validation\Rule\File\Size;

beforeEach(function () {
    $this->validator = new Size(array( 'size' => '1M' ));
});

test('missing files', function () {
    $file = realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . 'file_that_does_not_exist.jpg';
    expect($this->validator->validate($file))->toBeFalse();
});

test('file', function () {
    $file = realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . 'real_jpeg_file.jpg';
    expect($this->validator->validate($file))->toBeTrue();

    // change size
    $this->validator->setOption(Size::OPTION_SIZE, '10K');
    expect($this->validator->validate($file))->toBeFalse();
});

test('size as number', function () {
    $file = realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . 'real_jpeg_file.jpg';
    $this->validator->setOption(Size::OPTION_SIZE, 1000000000000);
    expect($this->validator->validate($file))->toBeTrue();

    // change size
    $this->validator->setOption(Size::OPTION_SIZE, 10000);
    expect($this->validator->validate($file))->toBeFalse();
});
