<?php

namespace Sirius\Validation;

use Sirius\Validation\Rule\AbstractRule;

class ValueValidator
{

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
     * @var \Sirius\Validation\ErrorMessage
     */
    protected $errorMessagePrototype;

    /**
     * The rule collections for the validation
     *
     * @var \Sirius\Validation\RuleCollection
     */
    protected $rules;

    /**
     * The label of the value to be validated
     *
     * @var string
     */
    protected $label;


    public function __construct(
        RuleFactory $ruleFactory = null,
        ErrorMessage $errorMessagePrototype = null,
        $label = null
    ) {
        if (!$ruleFactory) {
            $ruleFactory = new RuleFactory();
        }
        $this->ruleFactory = $ruleFactory;
        if (!$errorMessagePrototype) {
            $errorMessagePrototype = new ErrorMessage();
        }
        $this->errorMessagePrototype = $errorMessagePrototype;
        if ($label) {
            $this->label = $label;
        }
        $this->rules = new RuleCollection;
    }

    public function setLabel($label = null)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Add 1 or more validation rules
     *
     * @example
     * // add multiple rules at once
     * $validator->add(array(
     *   'required',
     *   array('required', array('email', null, '{label} must be an email', 'Field B')),
     * ));
     *
     * // add multiple rules using a string
     * $validator->add('required | email');
     *
     * // add validator with options
     * $validator->add('minlength', array('min' => 2), '{label} should have at least {min} characters', 'Field label');
     *
     * // add validator with string and parameters as JSON string
     * $validator->add('minlength({"min": 2})({label} should have at least {min} characters)(Field label)');
     *
     * // add validator with string and parameters as query string
     * $validator->add('minlength(min=2)({label} should have at least {min} characters)(Field label)');
     *
     * @param string|callback $name
     * @param string|array $options
     * @param string $messageTemplate
     * @param string $label
     *
     * @return ValueValidator
     */
    public function add($name, $options = null, $messageTemplate = null, $label = null)
    {
        if (is_array($name) && !is_callable($name)) {
            return $this->addMultiple($name);
        }
        if (is_string($name)) {
            // rule was supplied like 'required | email'
            if (strpos($name, ' | ') !== false) {
                return $this->addMultiple(explode(' | ', $name));
            }
            // rule was supplied like this 'length(2,10)(error message template)(label)'
            if (strpos($name, '(') !== false) {
                list($name, $options, $messageTemplate, $label) = $this->parseRule($name);
            }
        }

        // check for the default label
        if (!$label && $this->label) {
            $label = $this->label;
        }

        $validator = $this->ruleFactory->createRule($name, $options, $messageTemplate, $label);

        return $this->addRule($validator);
    }

    /**
     * @param array $rules
     *
     * @return ValueValidator
     */
    public function addMultiple($rules)
    {
        foreach ($rules as $singleRule) {
            // make sure the rule is an array (the parameters of subsequent calls);
            $singleRule = is_array($singleRule) ? $singleRule : array(
                $singleRule
            );
            call_user_func_array(
                array(
                    $this,
                    'add'
                ),
                $singleRule
            );
        }

        return $this;
    }

    /**
     * @param AbstractValidator $validationRule
     *
     * @return ValueValidator
     */
    public function addRule(AbstractRule $validationRule)
    {
        $validationRule->setErrorMessagePrototype($this->errorMessagePrototype);
        $this->rules->attach($validationRule);

        return $this;
    }

    /**
     * Remove validation rule
     *
     * @param mixed $name
     *            rule name or true if all rules should be deleted for that selector
     * @param mixed $options
     *            rule options, necessary for rules that depend on params for their ID
     *
     * @throws \InvalidArgumentException
     * @internal param string $selector data selector
     * @return self
     */
    public function remove($name = true, $options = null)
    {
        if ($name === true) {
            $this->rules = new RuleCollection();

            return $this;
        }
        $validator = $this->ruleFactory->createRule($name, $options);
        $this->rules->detach($validator);

        return $this;
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
     *
     * @param string $ruleAsString
     *
     * @return array
     */
    protected function parseRule($ruleAsString)
    {
        $ruleAsString    = trim($ruleAsString);
        $options         = array();
        $messageTemplate = null;
        $label           = null;

        $name         = substr($ruleAsString, 0, strpos($ruleAsString, '('));
        $ruleAsString = substr($ruleAsString, strpos($ruleAsString, '('));
        $matches      = array();
        preg_match_all('/\(([^\)]*)\)/', $ruleAsString, $matches);

        if (isset($matches[1])) {
            if (isset($matches[1][0]) && $matches[1][0] !== '') {
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


    public function validate($value, $valueIdentifier = null, DataWrapper\WrapperInterface $context = null)
    {
        $this->messages = array();
        $isRequired     = false;

        // evaluate the required rules
        /* @var $rule \Sirius\Validation\Rule\AbstractValidator */
        foreach ($this->rules as $rule) {
            if ($rule instanceof Rule\Required) {
                $isRequired = true;

                if (!$this->validateRule($rule, $value, $valueIdentifier, $context)) {
                    return false;
                }
            }
        }

        // avoid future rule evaluations if value is null or empty string
        if ($this->isEmpty($value)) {
            return true;
        }

        // evaluate the non-required rules
        foreach ($this->rules as $rule) {
            if (!($rule instanceof Rule\Required)) {
                $this->validateRule($rule, $value, $valueIdentifier, $context);

                // if field is required and we have an error,
                // do not continue with the rest of rules
                if ($isRequired && count($this->messages)) {
                    break;
                }
            }
        }

        return count($this->messages) === 0;
    }

    private function validateRule($rule, $value, $valueIdentifier, $context)
    {
        $rule->setContext($context);
        if (!$rule->validate($value, $valueIdentifier)) {
            $this->addMessage($rule->getMessage());
            return false;
        }
        return true;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function addMessage($message)
    {
        array_push($this->messages, $message);

        return $this;
    }

    public function getRules()
    {
        return $this->rules;
    }

    protected function isEmpty($value)
    {
        return in_array($value, array(null, ''));
    }
}
