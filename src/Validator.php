<?php
namespace Sirius\Validation;


class Validator implements ValidatorInterface
{

    const RULE_REQUIRED = 'required';

    const RULE_REQUIRED_WITH = 'requiredwith';

    const RULE_REQUIRED_WITHOUT = 'requiredwithout';

    const RULE_REQUIRED_WHEN = 'requiredwhen';

    // string rules
    const RULE_ALPHA = 'alpha';

    const RULE_ALPHANUMERIC = 'alphanumeric';

    const RULE_ALPHANUMHYPHEN = 'alphanumhyphen';

    const RULE_LENGTH = 'length';

    const RULE_MAX_LENGTH = 'maxlength';

    const RULE_MIN_LENGTH = 'minlength';

    const RULE_FULLNAME = 'fullname';

    // array rules
    const RULE_ARRAY_LENGTH = 'arraylength';

    const RULE_ARRAY_MIN_LENGTH = 'arrayminlength';

    const RULE_ARRAY_MAX_LENGTH = 'arraymaxlength';

    const RULE_IN_LIST = 'inlist';

    const RULE_NOT_IN_LIST = 'notinlist';

    // date rules
    const RULE_DATE = 'date';

    const RULE_DATETIME = 'datetime';

    const RULE_TIME = 'time';

    // number rules
    const RULE_BETWEEN = 'between';

    const RULE_GREATER_THAN = 'greaterthan';

    const RULE_LESS_THAN = 'lessthan';

    const RULE_NUMBER = 'number';

    const RULE_INTEGER = 'integer';
    // regular expression rules
    const RULE_REGEX = 'regex';

    const RULE_NOT_REGEX = 'notregex';
    // other rules
    const RULE_EMAIL = 'email';

    const RULE_EMAIL_DOMAIN = 'emaildomain';

    const RULE_URL = 'url';

    const RULE_WEBSITE = 'website';

    const RULE_IP = 'ipaddress';

    const RULE_MATCH = 'match';

    const RULE_EQUAL = 'equal';

    const RULE_CALLBACK = 'callback';

    // files rules
    const RULE_FILE_EXTENSION = 'fileextension';
    const RULE_FILE_SIZE = 'filesize';
    const RULE_IMAGE = 'image';
    const RULE_IMAGE_HEIGHT = 'imageheight';
    const RULE_IMAGE_WIDTH = 'imagewidth';
    const RULE_IMAGE_RATIO = 'imageratio';
    // upload rules
    const RULE_UPLOAD_EXTENSION = 'uploadextension';
    const RULE_UPLOAD_SIZE = 'uploadsize';
    const RULE_UPLOAD_IMAGE = 'uploadimage';
    const RULE_UPLOAD_IMAGE_HEIGHT = 'uploadimageheight';
    const RULE_UPLOAD_IMAGE_WIDTH = 'uploadimagewidth';
    const RULE_UPLOAD_IMAGE_RATIO = 'uploadimageratio';

    /**
     * This is set after the validation is performed so, in case data
     * does not change, the validation is not executed again
     *
     * @var boolean
     */
    protected $wasValidated = false;

    /**
     * The validation rules
     *
     * @var array
     */
    protected $rules = array();

    /**
     * The error messages generated after validation or set manually
     *
     * @var array
     */
    protected $messages = array();

    /**
     * Will be used to construct the rules
     *
     * @var \Sirius\Validation\RuleFactory
     */
    protected $ruleFactory;

    /**
     * The prototype that will be used to generate the error message
     *
     * @var ErrorMessage
     */
    protected $errorMessagePrototype;

    /**
     * The object that will contain the data
     *
     * @var \Sirius\Validation\DataWrapper\WrapperInterface
     */
    protected $dataWrapper;

    function __construct(RuleFactory $ruleFactory = null, ErrorMessage $errorMessagePrototype = null)
    {
        if (!$ruleFactory) {
            $ruleFactory = new RuleFactory();
        }
        $this->ruleFactory = $ruleFactory;
        if (!$errorMessagePrototype) {
            $errorMessagePrototype = new ErrorMessage();
        }
        $this->errorMessagePrototype = $errorMessagePrototype;
    }

    /**
     * Retrieve the rule factory
     *
     * @return \Sirius\Validation\RuleFactory
     */
    function getRuleFactory()
    {
        return $this->ruleFactory;
    }

    /**
     * Sets the error message prototype that will be used when returning the error message
     * when validation fails.
     * This option can be used when you need translation
     *
     * @param ErrorMessage $errorMessagePrototype
     * @throws \InvalidArgumentException
     * @return \Sirius\Validation\Rule\AbstractValidator
     */
    function setErrorMessagePrototype(ErrorMessage $errorMessagePrototype)
    {
        $this->errorMessagePrototype = $errorMessagePrototype;
        return $this;
    }

    /**
     * Retrieve the error message prototype
     *
     * @return ErrorMessage
     */
    function getErroMessagePrototype()
    {
        return $this->errorMessagePrototype;
    }

    /**
     * Add 1 or more validation rules to a selector
     *
     * @example // add multiple rules at once
     *          $validator->add(array(
     *          'field_a' => 'required',
     *          'field_b' => array('required', array('email', null, '{label} must be an email', 'Field B')),
     *          ));
     *          // add multiple rules using arrays
     *          $validator->add('field', array('required', 'email'));
     *          // add multiple rules using a string
     *          $validator->add('field', 'required | email');
     *          // add validator with options
     *          $validator->add('field', 'minlength', array('min' => 2), '{label} should have at least {min} characters', 'Field');
     *          // add validator with string and parameters as JSON string
     *          $validator->add('field', 'minlength({"min": 2})({label} should have at least {min} characters)(Field)');
     *          // add validator with string and parameters as query string
     *          $validator->add('field', 'minlength(min=2)({label} should have at least {min} characters)(Field)');
     * @param string $selector
     * @param string|callback $name
     * @param string|array $options
     * @param string $messageTemplate
     * @param string $label
     * @throws \InvalidArgumentException
     * @return \Sirius\Validation\Validator
     */
    function add($selector, $name = null, $options = null, $messageTemplate = null, $label = null)
    {
        // the $selector is an associative array with $selector => $rules
        if (func_num_args() == 1) {
            if (!is_array($selector)) {
                throw new \InvalidArgumentException('If $selector is the only argument it must be an array');
            }

            foreach ($selector as $valueSelector => $rules) {
                // multiple rules were passed for the same $valueSelector
                if (is_array($rules)) {
                    foreach ($rules as $rule) {
                        // the rule is an array, this means it contains $name, $options, $messageTemplate, $label
                        if (is_array($rule)) {
                            array_unshift($rule, $valueSelector);
                            call_user_func_array(
                                array(
                                    $this,
                                    'add'
                                ),
                                $rule
                            );
                            // the rule is only the name of the validator
                        } else {
                            $this->add($valueSelector, $rule);
                        }
                    }
                    // a single rule was passed for the $valueSelector
                } else {
                    $this->add($valueSelector, $rules);
                }
            }
            return $this;
        }
        if (!isset($this->rules[$selector])) {
            $this->rules[$selector] = new ValueValidator($this->getRuleFactory(), $this->getErroMessagePrototype());
        }
        $args = func_get_args();
        array_shift($args);
        call_user_func_array(array($this->rules[$selector], 'add'), $args);
        return $this;
    }

    /**
     * Remove validation rule
     *
     * @param string $selector
     *            data selector
     * @param mixed $name
     *            rule name or true if all rules should be deleted for that selector
     * @param mixed $options
     *            rule options, necessary for rules that depend on params for their ID
     * @return self
     */
    function remove($selector, $name = true, $options = null)
    {
        if (!array_key_exists($selector, $this->rules)) {
            return $this;
        }
        /* @var $collection \Sirius\Validation\ValueValidator */
        $collection = $this->rules[$selector];
        $collection->remove($name, $options);
        return $this;
    }

    /**
     * The data wrapper will be used to wrap around the data passed to the validator
     * This way you can validate anything, not just arrays (which is the default)
     *
     * @return \Sirius\Validation\DataWrapper\WrapperInterface
     */
    function getDataWrapper()
    {
        if (!$this->dataWrapper) {
            $this->dataWrapper = new DataWrapper\ArrayWrapper();
        }
        return $this->dataWrapper;
    }

    function setData($data)
    {
        $this->getDataWrapper()->setData($data);
        $this->wasValidated = false;
        // reset messages
        $this->messages = array();
        return $this;
    }

    /**
     * Performs the validation
     *
     * @param mixed $data
     *            array to be validated
     * @return boolean
     */
    function validate($data = null)
    {
        if ($data !== null) {
            $this->setData($data);
        }
        // data was already validated, return the results immediately
        if ($this->wasValidated === true) {
            return $this->wasValidated and count($this->messages) === 0;
        }
        foreach ($this->rules as $selector => $valueValidator) {
            foreach ($this->getDataWrapper()->getItemsBySelector($selector) as $valueIdentifier => $value) {
                /* @var $valueValidator \Sirius\Validation\ValueValidator */
                if (!$valueValidator->validate($value, $valueIdentifier, $this->getDataWrapper())) {
                    foreach ($valueValidator->getMessages() as $message) {
                        $this->addMessage($valueIdentifier, $message);
                    }
                }
            }
        }
        $this->wasValidated = true;
        return $this->wasValidated and count($this->messages) === 0;
    }

    /**
     * Adds a messages to the list of error messages
     *
     * @param string $item
     *            data identifier (eg: 'email', 'addresses[0][state]')
     * @param string $message
     * @return self
     */
    function addMessage($item, $message = null)
    {
        if (!$message) {
            return;
        }
        if (!array_key_exists($item, $this->messages)) {
            $this->messages[$item] = array();
        }
        $this->messages[$item][] = $message;

        return $this;
    }

    /**
     * Clears the messages of an item
     *
     * @param string $item
     * @return self
     */
    function clearMessages($item = null)
    {
        if (is_string($item)) {
            if (array_key_exists($item, $this->messages)) {
                unset($this->messages[$item]);
            }
        } elseif ($item === null) {
            $this->messages = array();
        }
        return $this;
    }

    /**
     * Returns all validation messages
     *
     * @param string $item
     *            key of the messages array (eg: 'password', 'addresses[0][line_1]')
     * @return array
     */
    function getMessages($item = null)
    {
        if (is_string($item)) {
            return array_key_exists($item, $this->messages) ? $this->messages[$item] : array();
        }
        return $this->messages;
    }

    function getRules()
    {
        return $this->rules;
    }
}
