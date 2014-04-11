<?php

namespace Sirius\Validation;


class ComplexTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->validator = new Validator();
        $this->validator
            ->add('email', 'email | required') // does the order matter?
            ->add('email_confirm', 'required |  email | match(item=email)')
            ->add('password', 'required')
            ->add('password_confirm', 'required | match(item=password)')
            ->add('feedback', 'requiredwith(item=agree_to_provide_feedback)')
            ->add('birthday', 'requiredwhen', array('item' => 'email_confirm', 'rule' => 'Email'));
    }

    function notestWithCorrectData()
    {
        $data = array(
            'email' => 'me@domain.com',
            'email_confirm' => 'me@domain.com',
            'password' => '1234',
            'password_confirm' => '1234',
            'agree_to_provide_feedback' => true,
            'feedback' => 'This is great!',
            'birthday' => '1980-01-01'
        );
        $this->assertTrue($this->validator->validate($data));
    }

    function testWithInvalidData()
    {
        $data = array(
            'email_confirm' => 'me@domain.com',
            'password' => '1234',
            'password_confirm' => '123456',
            'agree_to_provide_feedback' => true,
        );
        $this->validator->validate($data);
        $messages = $this->validator->getMessages();
        //print_r($this->validator->getMessages());

        #$this->assertEquals('This field is required', (string)$messages['email'][0]);
        #$this->assertEquals('This input must match password', (string)$messages['email'][0]);
        #$this->assertEquals('This field is required', (string)$messages['feedback'][0]);

    }

}
