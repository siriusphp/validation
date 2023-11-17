<?php

use \Sirius\Validation\Rule\Upload\Required;

beforeEach(function () {
    $this->validator = new Required();
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

test('upload ok', function () {
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

test('upload not ok', function () {
    $fileName = 'real_jpeg_file.jpg';
    $file     = array(
        'name'     => $fileName,
        'type'     => 'not_required',
        'size'     => 'not_required',
        'tmp_name' => realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . $fileName,
        'error'    => UPLOAD_ERR_PARTIAL
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
    expect($this->validator->validate($file))->toBeFalse();
});

test('file', function () {
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
