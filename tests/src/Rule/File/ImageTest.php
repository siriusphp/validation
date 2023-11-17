<?php

use \Sirius\Validation\Rule\File\Extension;
use \Sirius\Validation\Rule\File\Image;

beforeEach(function () {
    $this->validator = new Image();
});

test('missing files', function () {
    $file = realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . 'file_that_does_not_exist.jpg';
    expect($this->validator->validate($file))->toBeFalse();
});

test('real image', function () {
    $this->validator->setOption(Extension::OPTION_ALLOWED_EXTENSIONS, array( 'jpg' ));
    $file = realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . 'real_jpeg_file.jpg';
    expect($this->validator->validate($file))->toBeTrue();
});

test('fake image', function () {
    $this->validator->setOption(Extension::OPTION_ALLOWED_EXTENSIONS, array( 'jpg' ));
    $file = realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . 'fake_jpeg_file.jpg';
    expect($this->validator->validate($file))->toBeFalse();
});

test('extensions as string', function () {
    $this->validator->setOption(Extension::OPTION_ALLOWED_EXTENSIONS, 'GIF, jpg');
    $file = realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . 'real_jpeg_file.jpg';
    expect($this->validator->validate($file))->toBeTrue();
});

test('potential message', function () {
    $this->validator->setOption(Extension::OPTION_ALLOWED_EXTENSIONS, array( 'jpg', 'png' ));
    $this->validator->validate('no_file.jpg');
    expect((string) $this->validator->getPotentialMessage())->toEqual('The file is not a valid image (only JPG, PNG are allowed)');
});
