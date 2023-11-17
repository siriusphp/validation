<?php

use \Sirius\Validation\Rule\File\Extension;

beforeEach(function () {
    $this->validator = new Extension();
});

test('existing files', function () {
    $this->validator->setOption(Extension::OPTION_ALLOWED_EXTENSIONS, array( 'jpg' ));
    $file = realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . 'real_jpeg_file.jpg';
    expect($this->validator->validate($file))->toBeTrue();
});

test('missing files', function () {
    $this->validator->setOption(Extension::OPTION_ALLOWED_EXTENSIONS, array( 'jpg' ));
    $file = realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . 'file_that_does_not_exist.jpg';
    expect($this->validator->validate($file))->toBeFalse();
});

test('set option as string', function () {
    $this->validator->setOption(Extension::OPTION_ALLOWED_EXTENSIONS, 'jpg, GIF');
    $file = realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . 'real_jpeg_file.jpg';
    expect($this->validator->validate($file))->toBeTrue();
});

test('potential message', function () {
    $this->validator->setOption(Extension::OPTION_ALLOWED_EXTENSIONS, array( 'jpg', 'png' ));
    $this->validator->validate('no_file.jpg');
    expect((string) $this->validator->getPotentialMessage())->toEqual('The file does not have an acceptable extension (JPG, PNG)');
});
