<?php
namespace Sirius\Validation;

use Sirius\Validation\ValidatorInterface;

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
    const RULE_UPLOAD_REQUIRED = 'uploadrequired';
    const RULE_UPLOAD_EXTENSION = 'uploadextension';
    const RULE_UPLOAD_SIZE = 'uploadsize';
    const RULE_UPLOAD_IMAGE = 'uploadimage';
    const RULE_UPLOAD_IMAGE_HEIGHT = 'uploadimageheight';
    const RULE_UPLOAD_IMAGE_WIDTH = 'uploadimagewidth';
    const RULE_UPLOAD_IMAGE_RATIO = 'uploadimageratio';

    /**
     * @var boolean
     */
    protected $wasValidated = false;

    /**
     * @var array
     */
    protected $rules = array();

    /**
     * @var array
     */
    protected $messages = array();

    /**
     * @var \Sirius\Validation\RuleFactory
     */
    protected $ruleFactory;

    /**
     * @var ErrorMessage
     */
    protected $errorMessagePrototype;

    /**
     * The object that will contain the data
     *
     * @var \Sirius\Validation\DataWrapper\WrapperInterface
     */
    protected $dataWrapper;

    public function __construct(RuleFactory $ruleFactory = null, ErrorMessage $errorMessagePrototype = null)
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
    public function getRuleFactory()
    {
        return $this->ruleFactory;
    }

    /**
     * @param ErrorMessage $errorMessagePrototype
     *
     * @throws \InvalidArgumentException
     *
     * @return \Sirius\Validation\Rule\AbstractValidator
     */
    public function setErrorMessagePrototype(ErrorMessage $errorMessagePrototype)
    {
        $this->errorMessagePrototype = $errorMessagePrototype;

        return $this;
    }

    /**
     * Retrieve the error message prototype
     *
     * @return ErrorMessage
     */
    public function getErroMessagePrototype()
    {
        return $this->errorMessagePrototype;
    }

    /**
     * @example
     * // add multiple rules at once
     * $validator->add(array(
     *   'field_a' => 'required',
     *   'field_b' => array('required', array('email', null, '{label} must be an email', 'Field B')),
     * ));
     *
     * // add multiple rules using arrays
     * $validator->add('field', array('required', 'email'));
     *
     * // add multiple rules using a string
     * $validator->add('field', 'required | email');
     *
     * // add validator with options
     * $validator->add('field:Label', 'minlength', array('min' => 2), '{label} should have at least {min} characters');
     *
     * // add validator with string and parameters as JSON string
     * $validator->add('field:Label', 'minlength({"min": 2})({label} should have at least {min} characters)');
     *
     * // add validator with string and parameters as query string
     * $validator->add('field:label', 'minlength(min=2)({label} should have at least {min} characters)');
     *
     * @param string|array $selector
     * @param string|callback $name
     * @param string|array $options
     * @param string $messageTemplate
     * @param string $label
     *
     * @throws \InvalidArgumentException
     *
     * @return Validator
     */
    public function add($selector, $name = null, $options = null, $messageTemplate = null, $label = null)
    {
        // the $selector is an associative array with $selector => $rules
        if (func_num_args() == 1) {
            if (!is_array($selector)) {
                throw new \InvalidArgumentException('If $selector is the only argument it must be an array');
            }

            return $this->addMultiple($selector);
        }

        // check if the selector is in the form of 'selector:Label'
        if (strpos($selector, ':') !== false) {
            list($selector, $label) = explode(':', $selector, 2);
        }

        $this->ensureSelectorRulesExist($selector, $label);
        call_user_func(array( $this->rules[$selector], 'add' ), $name, $options, $messageTemplate, $label);

        return $this;
    }

    /**
     * @param array $selectorRulesCollection
     *
     * @return Validator
     */
    public function addMultiple($selectorRulesCollection)
    {
        foreach ($selectorRulesCollection as $selector => $rules) {
            // a single rule was passed for the $valueSelector
            if (! is_array($rules)) {
                $this->add($selector, $rules);
                continue;
            }

            // multiple rules were passed for the same $valueSelector
            foreach ($rules as $rule) {
                // the rule is an array, this means it contains $name, $options, $messageTemplate, $label
                if (is_array($rule)) {
                    array_unshift($rule, $selector);
                    call_user_func_array(
                        array(
                            $this,
                            'add'
                        ),
                        $rule
                    );
                    // the rule is only the name of the validator
                } else {
                    $this->add($selector, $rule);
                }
            }
        }

        return $this;
    }

    /**
     * @param string $selector
     *            data selector
     * @param mixed $name
     *            rule name or true if all rules should be deleted for that selector
     * @param mixed $options
     *            rule options, necessary for rules that depend on params for their ID
     *
     * @return self
     */
    public function remove($selector, $name = true, $options = null)
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
     * @param mixed $data
     *
     * @return \Sirius\Validation\DataWrapper\WrapperInterface
     */
    public function getDataWrapper($data = null)
    {
        // if $data is set reconstruct the data wrapper
        if (!$this->dataWrapper || $data) {
            $this->dataWrapper = new DataWrapper\ArrayWrapper($data);
        }

        return $this->dataWrapper;
    }

    public function setData($data)
    {
        $this->getDataWrapper($data);
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
     *
     * @return boolean
     */
    public function validate($data = null)
    {
        if ($data !== null) {
            $this->setData($data);
        }
        // data was already validated, return the results immediately
        if ($this->wasValidated === true) {
            return $this->wasValidated && count($this->messages) === 0;
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

        return $this->wasValidated && count($this->messages) === 0;
    }

    /**
     * @param string $item
     *            data identifier (eg: 'email', 'addresses[0][state]')
     * @param string $message
     *
     * @return self
     */
    public function addMessage($item, $message = null)
    {
        if ($message === null || $message === '') {
            return $this;
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
     *
     * @return self
     */
    public function clearMessages($item = null)
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
     * @param string $item
     *            key of the messages array (eg: 'password', 'addresses[0][line_1]')
     *
     * @return array
     */
    public function getMessages($item = null)
    {
        if (is_string($item)) {
            return array_key_exists($item, $this->messages) ? $this->messages[$item] : array();
        }

        return $this->messages;
    }

    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @param string $selector
     * @param string $label
     */
    protected function ensureSelectorRulesExist($selector, $label = null)
    {
        if (!isset($this->rules[$selector])) {
            $this->rules[$selector] = new ValueValidator(
                $this->getRuleFactory(),
                $this->getErroMessagePrototype(),
                $label
            );
        }
    }
}
