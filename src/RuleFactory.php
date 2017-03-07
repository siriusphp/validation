<?php

namespace Sirius\Validation;

use Sirius\Validation\Rule\Callback as CallbackRule;

class RuleFactory
{
    /**
     * Validator map allows for flexibility when creating a validation rule
     * You can use 'required' instead of 'required' for the name of the rule
     * or 'minLength'/'minlength' instead of 'MinLength'
     *
     * @var array
     */
    protected $validatorsMap = array();

    /**
     * @var array
     */
    protected $errorMessages = array();

    /**
     * @var array
     */
    protected $labeledErrorMessages = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->registerDefaultRules();
    }

    /**
     * Set up the default rules that come with the library
     */
    protected function registerDefaultRules()
    {
        $rulesClasses = array(
            'Alpha',
            'AlphaNumeric',
            'AlphaNumHyphen',
            'ArrayLength',
            'ArrayMaxLength',
            'ArrayMinLength',
            'Between',
            'Callback',
            'Date',
            'DateTime',
            'Email',
            'EmailDomain',
            'Equal',
            'FullName',
            'GreaterThan',
            'InList',
            'Integer',
            'IpAddress',
            'Length',
            'LessThan',
            'Match',
            'MaxLength',
            'MinLength',
            'NotInList',
            'NotRegex',
            'Number',
            'Regex',
            'Required',
            'RequiredWhen',
            'RequiredWith',
            'RequiredWithout',
            'Time',
            'Url',
            'Website',
            'File\Extension',
            'File\Image',
            'File\ImageHeight',
            'File\ImageRatio',
            'File\ImageWidth',
            'File\Size',
            'Upload\Required',
            'Upload\Extension',
            'Upload\Image',
            'Upload\ImageHeight',
            'Upload\ImageRatio',
            'Upload\ImageWidth',
            'Upload\Size',
        );
        foreach ($rulesClasses as $class) {
            $fullClassName       = '\\' . __NAMESPACE__ . '\Rule\\' . $class;
            $name                = strtolower(str_replace('\\', '', $class));
            $errorMessage        = constant($fullClassName . '::MESSAGE');
            $labeledErrorMessage = constant($fullClassName . '::LABELED_MESSAGE');
            $this->register($name, $fullClassName, $errorMessage, $labeledErrorMessage);
        }
    }


    /**
     * Register a class to be used when creating validation rules
     *
     * @param string $name
     * @param string $class
     *
     * @return \Sirius\Validation\RuleFactory
     */
    public function register($name, $class, $errorMessage = '', $labeledErrorMessage = '')
    {
        if (is_subclass_of($class, '\Sirius\Validation\Rule\AbstractRule')) {
            $this->validatorsMap[$name] = $class;
        }
        if ($errorMessage) {
            $this->errorMessages[$name] = $errorMessage;
        }
        if ($labeledErrorMessage) {
            $this->labeledErrorMessages[$name] = $labeledErrorMessage;
        }

        return $this;
    }

    /**
     * Factory method to construct a validator based on options that are used most of the times
     *
     * @param string|callable $name
     *            name of a validator class or a callable object/function
     * @param string|array $options
     *            validator options (an array, JSON string or QUERY string)
     * @param string $messageTemplate
     *            error message template
     * @param string $label
     *            label of the form input field or model attribute
     *
     * @throws \InvalidArgumentException
     * @return \Sirius\Validation\Rule\AbstractValidator
     */
    public function createRule($name, $options = null, $messageTemplate = null, $label = null)
    {
        $validator = $this->construcRuleByNameAndOptions($name, $options);

        // no message template, try to get it from the registry
        if (!$messageTemplate) {
            $messageTemplate = $this->getSuggestedMessageTemplate($name, !!$label);
        }

        if (is_string($messageTemplate) && $messageTemplate !== '') {
            $validator->setMessageTemplate($messageTemplate);
        }
        if (is_string($label) && $label !== '') {
            $validator->setOption('label', $label);
        }

        return $validator;
    }

    /**
     * Set default error message for a rule
     *
     * @param string $rule
     * @param string|null $messageWithoutLabel
     * @param string|null $messageWithLabel
     *
     * @return $this
     */
    public function setMessages($rule, $messageWithoutLabel = null, $messageWithLabel = null)
    {
        if ($messageWithoutLabel) {
            $this->errorMessages[$rule] = $messageWithoutLabel;
        }
        if ($messageWithLabel) {
            $this->labeledErrorMessages[$rule] = $messageWithLabel;
        }

        return $this;
    }

    /**
     * Get the error message saved in the registry for a rule, where the message
     * is with or without a the label
     *
     * @param string $name name of the rule
     * @param bool $withLabel
     *
     * @return string|NULL
     */
    protected function getSuggestedMessageTemplate($name, $withLabel)
    {
        $noLabelMessage = is_string($name) && isset($this->errorMessages[$name]) ? $this->errorMessages[$name] : null;
        if ($withLabel) {
            return is_string($name) && isset($this->labeledErrorMessages[$name]) ?
                $this->labeledErrorMessages[$name] :
                $noLabelMessage;
        }

        return $noLabelMessage;
    }

    /**
     * @param $name
     * @param $options
     *
     * @return CallbackRule
     */
    protected function construcRuleByNameAndOptions($name, $options)
    {
        if (is_callable($name)) {
            $validator = new CallbackRule(
                array(
                    'callback'  => $name,
                    'arguments' => $options
                )
            );
        } elseif (is_string($name)) {
            $name = trim($name);
            // use the validator map
            if (isset($this->validatorsMap[strtolower($name)])) {
                $name = $this->validatorsMap[strtolower($name)];
            }
            // try if the validator is the name of a class in the package
            if (class_exists('\Sirius\Validation\Rule\\' . $name, false)) {
                $name = '\Sirius\Validation\Rule\\' . $name;
            }
            // at this point we should have a class that can be instanciated
            if (class_exists($name) && is_subclass_of($name, '\Sirius\Validation\Rule\AbstractRule')) {
                $validator = new $name($options);
            }
        }

        if (!isset($validator)) {
            throw new \InvalidArgumentException(
                sprintf('Impossible to determine the validator based on the name: %s', (string) $name)
            );
        }

        return $validator;
    }
}
