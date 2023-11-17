<?php

use \Sirius\Validation\Rule\Upload\Extension;
use \Sirius\Validation\Rule\Upload\Image;

beforeEach(function () {
    $this->validator = new Image();
});

test('missing files', function () {
    $fileName = 'file_that_does_not_exist.jpg';
    $file     = array(
        'name'     => $fileName,
        'type'     => 'not_required',
        'size'     => 'not_required',
        'tmp_name' => realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . $fileName,
        'error'    => UPLOAD_ERR_OK
    );
    expect($this->validator->validate($file))->toBeFalse();
});

test('no upload', function () {
    $file     = array(
        'name'     => 'not_required',
        'type'     => 'not_required',
        'size'     => 'not_required',
        'tmp_name' => 'not_required',
        'error'    => UPLOAD_ERR_NO_FILE
    );
    expect($this->validator->validate($file))->toBeTrue();
});

test('real image', function () {
    $this->validator->setOption(Extension::OPTION_ALLOWED_EXTENSIONS, array( 'jpg' ));
    $fileName = 'real_jpeg_file.jpg';
    $file     = array(
        'name'     => $fileName,
        'type'     => 'not_required',
        'size'     => 'not_required',
        'tmp_name' => realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . $fileName,
        'error'    => UPLOAD_ERR_OK
    );
    expect($this->validator->validate($file))->toBeTrue();
});

test('fake image', function () {
    $this->validator->setOption(Extension::OPTION_ALLOWED_EXTENSIONS, array( 'jpg' ));
    $fileName = 'fake_jpeg_file.jpg';
    $file     = array(
        'name'     => $fileName,
        'type'     => 'not_required',
        'size'     => 'not_required',
        'tmp_name' => realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . $fileName,
        'error'    => UPLOAD_ERR_OK
    );
    expect($this->validator->validate($file))->toBeFalse();
});

test('extensions as string', function () {
    $this->validator->setOption(Extension::OPTION_ALLOWED_EXTENSIONS, 'GIF, jpg');
    $fileName = 'real_jpeg_file.jpg';
    $file     = array(
        'name'     => $fileName,
        'type'     => 'not_required',
        'size'     => 'not_required',
        'tmp_name' => realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . $fileName,
        'error'    => UPLOAD_ERR_OK
    );
    expect($this->validator->validate($file))->toBeTrue();
});

test('potential message', function () {
    $this->validator->setOption(Extension::OPTION_ALLOWED_EXTENSIONS, array( 'jpg', 'png' ));
    expect((string) $this->validator->getPotentialMessage())->toEqual('The file is not a valid image (only JPG, PNG are allowed)');
});
