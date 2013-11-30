<?php
namespace Sirius\Validation\Validator;

abstract class AbstractValidator
{

    protected static $defaultMessageTemplate = 'Value is not valid';

    protected $context;

    protected $options = array();

    protected $messageTemplate;

    protected $success = false;

    protected $value;

    protected $errorMessagePrototype;

    static function setDefaultMessageTemplate($message)
    {
        self::$defaultMessageTemplate = (string) $message;
    }

    function __construct($options = array())
    {
        foreach ($options as $k => $v) {
            $this->setOption($k, $v);
        }
    }

    function setOption($name, $value)
    {
        $this->options[$name] = $value;
        return $this;
    }

    function setContext($context)
    {
        $this->context = $context;
        return $this;
    }

    function setMessageTemplate($messageTemplate)
    {
        $this->messageTemplate = $messageTemplate;
        return $this;
    }

    function getMessageTemplate()
    {
        if (! $this->messageTemplate) {
            return static::$defaultMessageTemplate;
        }
        return $this->messageTemplate;
    }

    abstract function validate($value, $valueIdentifier = null);

    function setErrorMessagePrototype($errorMessagePrototype)
    {
        if (! $errorMessagePrototype instanceof \Sirius\Validation\ErrorMessage) {
            throw new \InvalidArgumentException('The error message prototype must be an instance of \Sirius\Validation\ErrorMessage');
        }
        $this->errorMessagePrototype = $errorMessagePrototype;
        return $this;
    }

    /**
     *
     * @return \Sirius\Validation\ErrorMessage
     */
    function getErrorMessagePrototype()
    {
        if (! $this->errorMessagePrototype) {
            $this->errorMessagePrototype = new \Sirius\Validation\ErrorMessage($this->getMessageTemplate());
        }
        return $this->errorMessagePrototype;
    }

    /**
     *
     * @return NULL \Sirius\Validation\ErrorMessage
     */
    function getMessage()
    {
        if ($this->success) {
            return null;
        }
        $message = $this->getPotentialMessage();
        $message->setVariables(array(
            'value' => $this->value
        ));
        return $message;
    }

    /**
     *
     * @return \Sirius\Validation\ErrorMessage
     */
    function getPotentialMessage()
    {
        $message = clone ($this->getErrorMessagePrototype());
        $message->setVariables($this->options);
        return $message;
    }
}