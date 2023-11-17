<?php
use \Sirius\Validation\Rule\Upload\Size;

beforeEach(function () {
    $this->validator = new Size(array( 'size' => '1M' ));
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

    // change size
    $this->validator->setOption(Size::OPTION_SIZE, '10K');
    expect($this->validator->validate($file))->toBeFalse();
});

test('size as number', function () {
    $fileName = 'real_jpeg_file.jpg';
    $file     = array(
        'name'     => $fileName,
        'type'     => 'not_required',
        'size'     => 'not_required',
        'tmp_name' => realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . $fileName,
        'error'    => UPLOAD_ERR_OK
    );
    $this->validator->setOption(Size::OPTION_SIZE, 1000000000000);
    expect($this->validator->validate($file))->toBeTrue();

    // change size
    $this->validator->setOption(Size::OPTION_SIZE, 10000);
    expect($this->validator->validate($file))->toBeFalse();
});
