<?php

use \Sirius\Validation\Rule\Upload\Extension;

beforeEach(function () {
    $this->validator = new Extension();
});

test('existing files', function () {
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

test('missing files', function () {
    $this->validator->setOption(Extension::OPTION_ALLOWED_EXTENSIONS, array( 'jpg' ));
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

test('set option as string', function () {
    $this->validator->setOption(Extension::OPTION_ALLOWED_EXTENSIONS, 'jpg, GIF');
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
    expect((string) $this->validator->getPotentialMessage())->toEqual('The file does not have an acceptable extension (JPG, PNG)');
});
