<?php

use \Sirius\Validation\Rule\Upload\ImageRatio;

beforeEach(function () {
    $this->validator = new ImageRatio(array( 'ratio' => 1 ));
});

test('missing files', function () {
    $fileName = 'file_that_does_not_exist.gif';
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

test('square', function () {
    $fileName = 'square_image.gif';
    $file     = array(
        'name'     => $fileName,
        'type'     => 'not_required',
        'size'     => 'not_required',
        'tmp_name' => realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . $fileName,
        'error'    => UPLOAD_ERR_OK
    );
    expect($this->validator->validate($file))->toBeTrue();
});

test('almost square', function () {
    $fileName = 'almost_square_image.gif';
    $file     = array(
        'name'     => $fileName,
        'type'     => 'not_required',
        'size'     => 'not_required',
        'tmp_name' => realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . $fileName,
        'error'    => UPLOAD_ERR_OK
    );
    expect($this->validator->validate($file))->toBeFalse();

    // change the error margin
    $this->validator->setOption(ImageRatio::OPTION_ERROR_MARGIN, 0.2);
    expect($this->validator->validate($file))->toBeTrue();
});

test('ratio zero', function () {
    $this->validator->setOption(ImageRatio::OPTION_RATIO, 0);
    $fileName = 'almost_square_image.gif';
    $file     = array(
        'name'     => $fileName,
        'type'     => 'not_required',
        'size'     => 'not_required',
        'tmp_name' => realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . $fileName,
        'error'    => UPLOAD_ERR_OK
    );
    expect($this->validator->validate($file))->toBeTrue();
});

test('invalid ratio', function () {
    $this->validator->setOption(ImageRatio::OPTION_RATIO, 'abc');
    $fileName = 'almost_square_image.gif';
    $file     = array(
        'name'     => $fileName,
        'type'     => 'not_required',
        'size'     => 'not_required',
        'tmp_name' => realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . $fileName,
        'error'    => UPLOAD_ERR_OK
    );
    expect($this->validator->validate($file))->toBeTrue();
});

test('ratio as string', function () {
    $this->validator->setOption(ImageRatio::OPTION_RATIO, '4:3');
    $fileName = '4_by_3_image.jpg';
    $file     = array(
        'name'     => $fileName,
        'type'     => 'not_required',
        'size'     => 'not_required',
        'tmp_name' => realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . $fileName,
        'error'    => UPLOAD_ERR_OK
    );
    expect($this->validator->validate($file))->toBeTrue();
});

test('file not an image', function () {
    $this->validator->setOption(ImageRatio::OPTION_RATIO, '4:3');
    $fileName = 'corrupt_image.jpg';
    $file     = array(
        'name'     => $fileName,
        'type'     => 'not_required',
        'size'     => 'not_required',
        'tmp_name' => realpath(__DIR__ . '/../../../fixitures/') . DIRECTORY_SEPARATOR . $fileName,
        'error'    => UPLOAD_ERR_OK
    );
    expect($this->validator->validate($file))->toBeFalse();
});
