<?php

namespace Latinosoft\Validation;

use PHPUnit\Framework\TestCase;

class ComplexTest extends TestCase
{

    function setUp(): void
    {
        $this->validator = new Validator();
        $this->validator
            ->add('email', 'email | required')// does the order matter?
            ->add('email_confirm', 'required |  email | match(item=email)')
            ->add('password', 'required')
            ->add('password_confirm', 'required | match(item=password)')
            ->add('feedback', 'requiredwith(item=agree_to_provide_feedback)')
            ->add('birthday', 'requiredwhen', array( 'item' => 'email_confirm', 'rule' => 'Email' ))
            // the lines below don't match the example but that's ok,
            // the individual rules have tests
            ->add('lines[*][price]', 'requiredwith(item=lines[*][quantity])');
    }

    function notestWithCorrectData()
    {
        $data = array(
            'email'                     => 'me@domain.com',
            'email_confirm'             => 'me@domain.com',
            'password'                  => '1234',
            'password_confirm'          => '1234',
            'agree_to_provide_feedback' => true,
            'feedback'                  => 'This is great!',
            'birthday'                  => '1980-01-01',
            'lines'                     => array(
                array( 'quantity' => 10, 'price' => 20 )
            )
        );
        $this->assertTrue($this->validator->validate($data));
    }

    function testWithInvalidData()
    {
        $data = array(
            'email_confirm'             => 'me@domain.com',
            'password'                  => '1234',
            'password_confirm'          => '123456',
            'agree_to_provide_feedback' => true,
            'lines'                     => array(
                array( 'quantity' => 10, 'price' => null )
            )
        );
        $this->validator->validate($data);
        $messages = $this->validator->getMessages();

        $this->assertEquals('This field is required', (string) $messages['email'][0]);
        $this->assertEquals('This input does not match password', (string) $messages['password_confirm'][0]);
        $this->assertEquals('This field is required', (string) $messages['feedback'][0]);
        $this->assertEquals('This field is required', (string) $messages['lines[0][price]'][0]);
    }

}
