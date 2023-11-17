<?php

uses(\Sirius\Validation\ErrorMessage::class);
use \Sirius\Validation\CustomErrorMessage;
use \Sirius\Validation\Validator;

function __toString()
{
    return '!!!' . __toString();
}

beforeEach(function () {
    $this->validator = new Validator();
    $this->validator->setErrorMessagePrototype(new CustomErrorMessage());
});

test('error message', function () {
    $this->validator->add('email', 'email');
    $this->validator->validate(array( 'email' => 'not_an_email' ));

    $messages = $this->validator->getMessages('email');
    expect(count($messages))->toEqual(1);

    expect((string) $messages[0])->toEqual('!!!This input must be a valid email address');
});
