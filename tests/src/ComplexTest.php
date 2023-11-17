<?php

use \Sirius\Validation\Validator;

beforeEach(function () {
    $this->validator = new Validator();
    $this->validator
        ->add('email', 'email | required')// does the order matter?
        ->add('email_confirm', 'required |  email | match(item=email)')
        ->add('password', 'required | notmatch(item=email)')
        ->add('password_confirm', 'required | match(item=password)')
        ->add('feedback', 'requiredwith(item=agree_to_provide_feedback)')
        ->add('birthday', 'requiredwhen', array( 'item' => 'email_confirm', 'rule' => 'Email' ))
        // the lines below don't match the example but that's ok,
        // the individual rules have tests
        ->add('lines[*][price]', 'requiredwith(item=lines[*][quantity])');
});

test('with invalid data', function () {
    $data = array(
        'email'                     => 'me@domain.com',
        'password'                  => 'me@domain.com',
        'password_confirm'          => '123456',
        'agree_to_provide_feedback' => true,
        'lines'                     => array(
            array( 'quantity' => 10, 'price' => null )
        )
    );
    $this->validator->validate($data);
    $messages = $this->validator->getMessages();

    expect((string) $messages['email_confirm'][0])->toEqual('This field is required');
    expect((string) $messages['password'][0])->toEqual('This input does match email');
    expect((string) $messages['password_confirm'][0])->toEqual('This input does not match password');
    expect((string) $messages['feedback'][0])->toEqual('This field is required');
    expect((string) $messages['lines[0][price]'][0])->toEqual('This field is required');
});
