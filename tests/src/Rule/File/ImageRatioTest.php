<?php

use \Sirius\Validation\Rule\File\ImageRatio;

beforeEach(function () {
    $this->validator = new ImageRatio(array( 'ratio' => 1 ));
});

test('missing files', function () {
    $file = realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . 'file_that_does_not_exist.jpg';
    expect($this->validator->validate($file))->toBeFalse();
});

test('square', function () {
    $file = realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . 'square_image.gif';
    expect($this->validator->validate($file))->toBeTrue();
});

test('almost square', function () {
    $file = realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . 'almost_square_image.gif';
    expect($this->validator->validate($file))->toBeFalse();

    // change the error margin
    $this->validator->setOption(ImageRatio::OPTION_ERROR_MARGIN, 0.2);
    expect($this->validator->validate($file))->toBeTrue();
});

test('ratio zero', function () {
    $this->validator->setOption(ImageRatio::OPTION_RATIO, 0);
    $file = realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . 'almost_square_image.gif';
    expect($this->validator->validate($file))->toBeTrue();
});

test('invalid ratio', function () {
    $this->validator->setOption(ImageRatio::OPTION_RATIO, 'abc');
    $file = realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . 'almost_square_image.gif';
    expect($this->validator->validate($file))->toBeTrue();
});

test('ratio as string', function () {
    $this->validator->setOption(ImageRatio::OPTION_RATIO, '4:3');
    $file = realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . '4_by_3_image.jpg';
    expect($this->validator->validate($file))->toBeTrue();
});

test('file not an image', function () {
    $this->validator->setOption(ImageRatio::OPTION_RATIO, '4:3');
    $file = realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . 'corrupt_image.jpg';
    expect($this->validator->validate($file))->toBeFalse();
});
