<?php
namespace Sirius\Validation;

use Sirius\Validation\Utils;
use Sirius\Validation\Helper;

class Validator
{

    /**
     * Validator map allows for flexibility when creating a validation rule
     * You can use 'required' instead of 'required' for the name of the rule
     * or 'minLength'/'minlength' instead of 'MinLength'
     *
     * @var array
     */
    protected $validatorsMap = array(
        'required' => 'Required',
        'email' => 'Email',
        'emaildomain' => 'EmailDomanin',
        'arraylength' => 'ArrayLength',
        'arraymaxlength' => 'ArrayMaxLength',
        'arrayminlength' => 'ArrayMinLengh',
        'between' => 'Between',
        'min' => 'Min',
        'max' => 'Max',
        'callback' => 'Callback',
        'fullname' => 'FullName',
        'inlist' => 'InList',
        'notinlist' => 'NotInList',
        'regex' => 'Regex',
        'notregex' => 'NotRegex',
        'url' => 'Url',
        'website' => 'Website'
    );

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
     * The prototype that will be used to generate the error message
     *
     * @var \Sirius\Validation\ErrorMessage
     */
    protected $errorMessagePrototype;

    function __construct($rules = null)
    {
        if (is_array($rules) or $rules instanceof Traversable) {
            foreach ($rules as $rule) {
                call_user_func_array(array(
                    $this,
                    'add'
                ), $rule);
            }
        }
    }

    /**
     * Sets the error message prototype that will be used when returning the error message
     * when validation fails.
     * This option can be used when you need translation
     *
     * @param \Sirius\Validation\ErrorMessage $errorMessagePrototype
     * @throws \InvalidArgumentException
     * @return \Sirius\Validation\Validator\AbstractValidator
     */
    function setErrorMessagePrototype(\Sirius\Validation\ErrorMessage $errorMessagePrototype)
    {
        $this->errorMessagePrototype = $errorMessagePrototype;
        return $this;
    }
    
    function getErroMessagePrototype() {
        if (!$this->errorMessagePrototype) {
            $this->errorMessagePrototype = new ErrorMessage();
        }
        return $this->errorMessagePrototype;
    }
    
    /**
     * Add 1 or more validation rules to a selector
     *
     * @example // add multiple rules at once
     *          $validator->add(array(
     *              'field_a' => 'required',
     *              'field_b' => array('required', array('email', null, '{label} must be an email', 'Field B')),
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
                            call_user_func_array(array($this, 'add'), $rule);
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
        if (is_array($name) && ! is_callable($name)) {
            foreach ($name as $singleRule) {
                // make sure the rule is an array (the parameters of subsequent calls);
                $singleRule = is_array($singleRule) ? $singleRule : array(
                    $singleRule
                );
                array_unshift($singleRule, $selector);
                call_user_func_array(array(
                    $this,
                    'add'
                ), $singleRule);
            }
            return $this;
        }
        if (is_string($name)) {
            // rule was supplied like 'required' or 'required | email'
            if (strpos($name, ' | ') !== false) {
                return $this->add($selector, explode(' | ', $name));
            }
            // rule was supplied like this 'length(2,10)(error message template)(label)'
            if (strpos($name, '(') !== false) {
                list ($name, $options, $messageTemplate, $label) = $this->parseRule($name);
            }
        }
        $validator = $this->createValidator($name, $options, $messageTemplate, $label);
        if (! array_key_exists($selector, $this->rules)) {
            $this->rules[$selector] = array();
        }
        if (! $this->hasValidator($selector, $validator)) {
            $this->rules[$selector][] = $validator;
        }
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
        if (! array_key_exists($selector, $this->rules)) {
            return $this;
        }
        if ($name === true) {
            unset($this->rules[$selector]);
        } else {
            $validator = $this->createValidator($name, $options);
            foreach ($this->rules[$selector] as $k => $v) {
                if ($v->getUniqueId() == $validator->getUniqueId()) {
                    unset($this->rules[$selector][$k]);
                    break;
                }
            }
        }
        return $this;
    }

    /**
     * Verify if a specific selector has a validator associated with it
     *
     * @param string $selector            
     * @param \Sirius\Validation\Validator\AbstractValidator $validator            
     * @return boolean
     */
    function hasValidator($selector, \Sirius\Validation\Validator\AbstractValidator $validator)
    {
        if (! array_key_exists($selector, $this->rules) || ! $this->rules[$selector]) {
            return false;
        }
        foreach ($this->rules[$selector] as $k => $v) {
            if ($v->getUniqueId() == $validator->getUniqueId()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Factory method to construct a validator based on options that are used most of the times
     *
     * @param string $name
     *            name of a validator class or a callable object/function
     * @param string|array $options
     *            validator options (an array, JSON string or QUERY string)
     * @param string $messageTemplate
     *            error message template
     * @param string $label
     *            label of the form input field or model attribute
     * @return \Sirius\Validation\Validator\AbstractValidator
     */
    protected function createValidator($name, $options = null, $messageTemplate = null, $label = null)
    {
        if ($options && is_string($options)) {
        	$startChar = substr($options, 0, 1);
        	if ($startChar == '{' || $startChar == '[') {
        		$options = json_decode($options, true);
        	} else {
        		parse_str($options, $output);
        		$options = $output;
        	}
        } elseif (!$options) {
        	$options = array();
        }
        
        if (!is_array($options)) {
        	throw new \InvalidArgumentException('Validator options should be an array, JSON string or query string');
        }
        
    	if (is_callable($name)) {
            $validator = new \Sirius\Validation\Validator\Callback(array(
                'callback' => $name,
                'arguments' => $options
            ));
        } else {
            // use the validator map
            if (isset($this->validatorsMap[strtolower($name)])) {
                $name = $this->validatorsMap[strtolower($name)];
            }
            if (class_exists('\Sirius\Validation\Validator\\' . $name)) {
                $name = '\Sirius\Validation\Validator\\' . $name;
            }
            if (class_exists($name) && is_subclass_of($name, '\Sirius\Validation\Validator\AbstractValidator')) {
                $validator = new $name($options);
            }
        }
        if (! isset($validator)) {
            throw new \InvalidArgumentException(sprintf('Impossible to determine the validator based on the name %s', (string) $name));
        }
        
        if ($messageTemplate) {
            $validator->setMessageTemplate($messageTemplate);
        }
        if ($label) {
            $validator->setOption('label', $label);
        }
        $validator->setErrorMessagePrototype($this->getErroMessagePrototype());
        return $validator;
    }

    /**
     * Converts a rule that was supplied as string into a set of options that define the rule
     *
     * @example 'minLength({"min":2})({label} must have at least {min} characters)(Street)'
     *         
     *          will be converted into
     *         
     *          array(
     *          'minLength', // validator name
     *          array('min' => 2'), // validator options
     *          '{label} must have at least {min} characters',
     *          'Street' // label
     *          )
     * @param string $ruleAsString            
     * @return array
     */
    protected function parseRule($ruleAsString)
    {
        $ruleAsString = trim($ruleAsString);
        $name = '';
        $options = array();
        $messageTemplate = null;
        $label = null;
        
        $name = substr($ruleAsString, 0, strpos($ruleAsString, '('));
        $ruleAsString = substr($ruleAsString, strpos($ruleAsString, '('));
        $matches = array();
        preg_match_all('/\(([^\)]*)\)/', $ruleAsString, $matches);
        
        if (isset($matches[1])) {
            if (isset($matches[1][0]) && $matches[1][0]) {
                $options = $matches[1][0];
            }
            if (isset($matches[1][1]) && $matches[1][1]) {
                $messageTemplate = $matches[1][1];
            }
            if (isset($matches[1][2]) && $matches[1][2]) {
                $label = $matches[1][2];
            }
        }
        
        return array(
            $name,
            $options,
            $messageTemplate,
            $label
        );
    }

    function setData($data)
    {
        if (is_object($data)) {
            if ($data instanceof \ArrayObject) {
                $data = $data->getArrayCopy();
            } elseif (method_exists($data, 'toArray')) {
                $data = $data->toArray();
            }
        }
        if (! is_array($data)) {
            throw new \InvalidArgumentException('Data passed to validator is not an array');
        }
        $this->data = $data;
        $this->wasValidated = false;
        // reset messages
        $this->messages = array();
        return $this;
    }

    /**
     * Performs the validation
     *
     * @param array|false $data
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
        // since we validate the data that it is found below
        // we need to ensure the required items are there
        $this->ensureRequiredItems();
        foreach ($this->data as $item => $value) {
            $this->validateItem($item);
        }
        $this->wasValidated = true;
        return $this->wasValidated and count($this->messages) === 0;
    }

    /**
     * Returns the value of an item from the data set
     *
     * @param string $item            
     * @return mixed
     */
    protected function getItemValue($item)
    {
        return Utils::arrayGetByPath($this->data, $item);
    }

    /**
     * Perform the validation on a single item
     * Does not return anything
     *
     * @param string $item
     *            Data selector
     * @return bool
     */
    function validateItem($item)
    {
        $value = $this->getItemValue($item);
        $requiredValidator = new \Sirius\Validation\Validator\Required();
        foreach ($this->rules as $selector => $selectorRules) {
            if ($this->itemMatchesSelector($item, $selector)) {
                foreach ($selectorRules as $ruleId => $rule) {
                    if (! $this->valueSatisfiesRule($value, $item, $rule)) {
                        $this->addMessage($item, $rule->getMessage());
                    }
                    // if field is required and we have an error,
                    // do not continue with the rest of rules
                    if ($this->hasValidator($selector, $requiredValidator) && array_key_exists($item, $this->messages) && count($this->messages[$item])) {
                        break;
                    }
                }
            }
        }
        // proceed with the validation one level deep
        if (is_array($value)) {
            foreach (array_keys($value) as $key) {
                $this->validateItem("{$item}[{$key}]");
            }
        }
        return count($this->getMessages($item)) == 0;
    }

    protected function ensureRequiredItems()
    {
        $requiredValidator = new \Sirius\Validation\Validator\Required();
        foreach ($this->rules as $selector => $selectorRules) {
            if ($this->hasValidator($selector, $requiredValidator)) {
                $this->data = Utils::arraySetBySelector($this->data, $selector, null, false);
            }
        }
    }

    protected function valueSatisfiesRule($value, $item, $rule)
    {
        return $rule->validate($value, $item);
    }

    protected function itemMatchesSelector($item, $selector)
    {
        if (strpos($selector, '*')) {
            $regex = '/' . str_replace('*', '[^\]]+', str_replace(array(
                '[',
                ']'
            ), array(
                '\[',
                '\]'
            ), $selector)) . '/';
            // cho ($item . '|' . $regex . "\n");
            return preg_match($regex, $item);
        } else {
            return $item == $selector;
        }
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
        if (! $message) {
            return;
        }
        if (! array_key_exists($item, $this->messages)) {
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
