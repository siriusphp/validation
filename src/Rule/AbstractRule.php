<?php
namespace Sirius\Validation\Rule;

use Sirius\Validation\DataWrapper\ArrayWrapper;
use Sirius\Validation\DataWrapper\WrapperInterface;
use Sirius\Validation\ErrorMessage;

abstract class AbstractRule
{
    // default error message when there is no LABEL attached
    const MESSAGE = 'Value is not valid';

    // default error message when there is a LABEL attached
    const LABELED_MESSAGE = '{label} is not valid';

    /**
     * The validation context
     * This is the data set that the data being validated belongs to
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
     * If you don't agree with the default messages that were provided
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
     * Stored in order to be passed to the errorMessage so that you get error
     * messages like '"abc" is not a valid email'
     *
     * @var mixed
     */
    protected $value;

    /**
     * The error message prototype that will be used to generate the error message
     *
     * @var ErrorMessage
     */
    protected $errorMessagePrototype;

    /**
     * Options map in case the options are passed as list instead of associative array
     *
     * @var array
     */
    protected $optionsIndexMap = array();

    public function __construct($options = array())
    {
        $options = $this->normalizeOptions($options);
        if (is_array($options) && ! empty($options)) {
            foreach ($options as $k => $v) {
                $this->setOption($k, $v);
            }
        }
    }

    /**
     * Method that parses the option variable and converts it into an array
     * You can pass anything to a validator like:
     * - a query string: 'min=3&max=5'
     * - a JSON string: '{"min":3,"max":5}'
     * - a CSV string: '5,true' (for this scenario the 'optionsIndexMap' property is required)
     *
     * @param mixed $options
     *
     * @return array
     * @throws \InvalidArgumentException
     */
    protected function normalizeOptions($options)
    {
        if ('0' === $options && count($this->optionsIndexMap) > 0) {
            $options = array($this->optionsIndexMap[0] => '0');
        }
        if (! $options) {
            return array();
        }

        if (is_array($options) && $this->arrayIsAssoc($options)) {
            return $options;
        }

        $result = $options;
        if ($options && is_string($options)) {
            $startChar = substr($options, 0, 1);
            if ($startChar == '{') {
                $result = json_decode($options, true);
            } elseif (strpos($options, '=') !== false) {
                $result = $this->parseHttpQueryString($options);
            } else {
                $result = $this->parseCsvString($options);
            }
        }

        if (! is_array($result)) {
            throw new \InvalidArgumentException('Validator options should be an array, JSON string or query string');
        }

        return $result;
    }

    /**
     * Converts a HTTP query string to an array
     *
     * @param $str
     *
     * @return array
     */
    protected function parseHttpQueryString($str)
    {
        parse_str($str, $arr);

        return $this->convertBooleanStrings($arr);
    }

    /**
     * Converts 'true' and 'false' strings to TRUE and FALSE
     *
     * @param $v
     *
     * @return bool|array
     */
    protected function convertBooleanStrings($v)
    {
        if (is_array($v)) {
            return array_map(array( $this, 'convertBooleanStrings' ), $v);
        }
        if ($v === 'true') {
            return true;
        }
        if ($v === 'false') {
            return false;
        }

        return $v;
    }

    /**
     * Parses a CSV string and converts the result into an "options" array
     * (an associative array that contains the options for the validation rule)
     *
     * @param $str
     *
     * @return array
     */
    protected function parseCsvString($str)
    {
        if (! isset($this->optionsIndexMap) || ! is_array($this->optionsIndexMap) || empty($this->optionsIndexMap)) {
            throw new \InvalidArgumentException(sprintf(
                'Class %s is missing the `optionsIndexMap` property',
                get_class($this)
            ));
        }

        $options = explode(',', $str);
        $result  = array();
        foreach ($options as $k => $v) {
            if (! isset($this->optionsIndexMap[$k])) {
                throw new \InvalidArgumentException(sprintf(
                    'Class %s does not have the index %d configured in the `optionsIndexMap` property',
                    get_class($this),
                    $k
                ));
            }
            $result[$this->optionsIndexMap[$k]] = $v;
        }

        return $this->convertBooleanStrings($result);
    }

    /**
     * Checks if an array is associative (ie: the keys are not numbers in sequence)
     *
     * @param array $arr
     *
     * @return bool
     */
    protected function arrayIsAssoc($arr)
    {
        return array_keys($arr) !== range(0, count($arr));
    }


    /**
     * Generates a unique string to identify the validator.
     * It is used to compare 2 validators so you don't add the same rule twice in a validator object
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
     *
     * @return \Sirius\Validation\Rule\AbstractRule
     */
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;

        return $this;
    }

    /**
     * Get an option for the validator.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getOption($name)
    {
        if (isset($this->options[$name])) {
            return $this->options[$name];
        } else {
            return null;
        }
    }

    /**
     * The context of the validator can be used when the validator depends on other values
     * that are not known at the moment the validator is constructed
     * For example, when you need to validate an email field matches another email field,
     * to confirm the email address
     *
     * @param array|object $context
     *
     * @throws \InvalidArgumentException
     * @return \Sirius\Validation\Rule\AbstractRule
     */
    public function setContext($context = null)
    {
        if ($context === null) {
            return $this;
        }
        if (is_array($context)) {
            $context = new ArrayWrapper($context);
        }
        if (! is_object($context) || ! $context instanceof WrapperInterface) {
            throw new \InvalidArgumentException(
                'Validator context must be either an array or an instance
                of Sirius\Validator\DataWrapper\WrapperInterface'
            );
        }
        $this->context = $context;

        return $this;
    }

    /**
     * Custom message for this validator to used instead of the the default one
     *
     * @param string $messageTemplate
     *
     * @return \Sirius\Validation\Rule\AbstractRule
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
        if ($this->messageTemplate) {
            return $this->messageTemplate;
        }
        if (isset($this->options['label'])) {
            return constant(get_class($this) . '::LABELED_MESSAGE');
        }

        return constant(get_class($this) . '::MESSAGE');
    }

    /**
     * Validates a value
     *
     * @param mixed $value
     * @param null|mixed $valueIdentifier
     *
     * @return mixed
     */
    abstract public function validate($value, $valueIdentifier = null);

    /**
     * Sets the error message prototype that will be used when returning the error message
     * when validation fails.
     * This option can be used when you need translation
     *
     * @param ErrorMessage $errorMessagePrototype
     *
     * @throws \InvalidArgumentException
     * @return \Sirius\Validation\Rule\AbstractRule
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
        if (! $this->errorMessagePrototype) {
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

    /**
     * Method for determining the path to a related item.
     * Eg: for `lines[5][price]` the related item `lines[*][quantity]`
     * has the value identifier as `lines[5][quantity]`
     *
     * @param $valueIdentifier
     * @param $relatedItem
     *
     * @return string|null
     */
    protected function getRelatedValueIdentifier($valueIdentifier, $relatedItem)
    {
        // in case we don't have a related path
        if (strpos($relatedItem, '*') === false) {
            return $relatedItem;
        }

        // lines[*][quantity] is converted to ['lines', '*', 'quantity']
        $relatedItemParts = explode('[', str_replace(']', '', $relatedItem));
        // lines[5][price] is ['lines', '5', 'price']
        $valueIdentifierParts = explode('[', str_replace(']', '', $valueIdentifier));

        if (count($relatedItemParts) !== count($valueIdentifierParts)) {
            return $relatedItem;
        }

        // the result should be ['lines', '5', 'quantity']
        $relatedValueIdentifierParts = array();
        foreach ($relatedItemParts as $index => $part) {
            if ($part === '*' && isset($valueIdentifierParts[$index])) {
                $relatedValueIdentifierParts[] = $valueIdentifierParts[$index];
            } else {
                $relatedValueIdentifierParts[] = $part;
            }
        }

        $relatedValueIdentifier = implode('][', $relatedValueIdentifierParts) . ']';
        $relatedValueIdentifier = str_replace(
            $relatedValueIdentifierParts[0] . ']',
            $relatedValueIdentifierParts[0],
            $relatedValueIdentifier
        );

        return $relatedValueIdentifier;
    }
}
