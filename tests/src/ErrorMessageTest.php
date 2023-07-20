<?php

namespace Sirius\Validation;

class CustomErrorMessage extends ErrorMessage
{

    function __toString()
    {
        return '!!!' . parent::__toString();
    }

}

class ErrorMessageTest extends \PHPUnit\Framework\TestCase
{

    protected function setUp(): void
    {
        $this->validator = new Validator();
        $this->validator->setErrorMessagePrototype(new CustomErrorMessage());
    }

    function testErrorMessage(): void
    {
        $this->validator->add('email', 'email');
        $this->validator->validate(array( 'email' => 'not_an_email' ));

        $messages = $this->validator->getMessages('email');
        $this->assertEquals(1, count($messages));

        $this->assertEquals('!!!This input must be a valid email address', (string) $messages[0]);
    }


}
