<?php
namespace Sirius\Validation\Rule;

use Sirius\Validation\DataWrapper\ArrayWrapper;
use Sirius\Validation\DataWrapper\WrapperInterface;
use Sirius\Validation\ErrorMessage;

abstract class AbstractValidator
{

    /**
     * Default error message template
     *
     * @var string
     */
    protected static $defaultMessageTemplate = 'Value is not valid';

    /**
     *
     * @var \Sirius\Validation\DataWrapper\WrapperInterface
     */
    protected $context;

    /**
     * Options for the validator.
     * Also passed to the error message for customization.
     *
     * @var array
     */
    protected $options = array();

    /**
     * Custom error message template for the validator instance
     *
     * @var string
     */
    protected $messageTemplate;

    /**
     * Result of the last validation
     *
     * @var boolean
     */
    protected $success = false;

    /**
     * Last value validated with the validator.
     * Stored in order to be passed to the errorMessage
     *
     * @var mixed
     */
    protected $value;

    /**
     * The prototype that will be used to generate the error message
     *
     * @var ErrorMessage
     */
    protected $errorMessagePrototype;

    /**
     * Set the global default message to be used by the validator
     *
     * @param string $message
     */
    public static function setDefaultMessageTemplate($message)
    {
        self::$defaultMessageTemplate = (string)$message;
    }

    public function __construct($options = array())
    {
        if (is_array($options) && !empty($options)) {
            foreach ($options as $k => $v) {
                $this->setOption($k, $v);
            }
        }
    }

    /**
     * Generates a unique string to identify the validator.
     * It can be used to compare 2 validators
     * (eg: so you don't add the same validator twice in a validator object)
     *
     * @return string
     */
    public function getUniqueId()
    {
        return get_called_class() . '|' . json_encode(ksort($this->options));
    }

    /**
     * Set an option for the validator.
     *
     * The options are also be passed to the error message.
     *
     * @param string $name
     * @param mixed $value
     * @return \Sirius\Validation\Rule\AbstractValidator
     */
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;
        return $this;
    }

    /**
     * The context of the validator can be used when the validator depends on other values
     * that are not known at the moment the validator is constructed
     * For example, when you need to validate an email field matches another email field,
     * to confirm the email address
     *
     * @param array|object $context
     * @throws \InvalidArgumentException
     * @return \Sirius\Validation\Rule\AbstractValidator
     */
    public function setContext($context = null)
    {
        if ($context === null) {
            return $this;
        }
        if (is_array($context)) {
            $context = new ArrayWrapper($context);
        }
        if (!is_object($context) || !$context instanceof WrapperInterface) {
            throw new \InvalidArgumentException(
                'Validator context must be either an array or an instance of Sirius\Validator\DataWrapper\WrapperInterface'
            );
        }
        $this->context = $context;
        return $this;
    }

    /**
     * Custom message for this validator to used instead of the the default one
     *
     * @param string $messageTemplate
     * @return \Sirius\Validation\Rule\AbstractValidator
     */
    public function setMessageTemplate($messageTemplate)
    {
        $this->messageTemplate = $messageTemplate;
        return $this;
    }

    /**
     * Retrieves the error message template (either the global one or the custom message)
     *
     * @return string
     */
    public function getMessageTemplate()
    {
        if (!$this->messageTemplate) {
            return static::$defaultMessageTemplate;
        }
        return $this->messageTemplate;
    }

    /**
     * Validates a value
     *
     * @param mixed $value
     * @param null|mixed $valueIdentifier
     * @return mixed
     */
    abstract function validate($value, $valueIdentifier = null);

    /**
     * Sets the error message prototype that will be used when returning the error message
     * when validation fails.
     * This option can be used when you need translation
     *
     * @param ErrorMessage $errorMessagePrototype
     * @throws \InvalidArgumentException
     * @return \Sirius\Validation\Rule\AbstractValidator
     */
    public function setErrorMessagePrototype(ErrorMessage $errorMessagePrototype)
    {
        $this->errorMessagePrototype = $errorMessagePrototype;
        return $this;
    }

    /**
     * Returns the error message prototype.
     * It constructs one if there isn't one.
     *
     * @return ErrorMessage
     */
    public function getErrorMessagePrototype()
    {
        if (!$this->errorMessagePrototype) {
            $this->errorMessagePrototype = new ErrorMessage();
        }
        return $this->errorMessagePrototype;
    }

    /**
     * Retrieve the error message if validation failed
     *
     * @return NULL|\Sirius\Validation\ErrorMessage
     */
    public function getMessage()
    {
        if ($this->success) {
            return null;
        }
        $message = $this->getPotentialMessage();
        $message->setVariables(
            array(
                'value' => $this->value
            )
        );
        return $message;
    }

    /**
     * Retrieve the potential error message.
     * Example: when you do client-side validation you need to access the "potential error message" to be displayed
     *
     * @return ErrorMessage
     */
    public function getPotentialMessage()
    {
        $message = clone ($this->getErrorMessagePrototype());
        $message->setTemplate($this->getMessageTemplate());
        $message->setVariables($this->options);
        return $message;
    }
}
