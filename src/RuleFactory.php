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
    protected $validatorsMap = array(
        Validator::RULE_REQUIRED => 'Required',
        Validator::RULE_REQUIRED_WITH => 'RequiredWith',
        Validator::RULE_REQUIRED_WITHOUT => 'RequiredWithout',
        Validator::RULE_REQUIRED_WHEN => 'RequiredWhen',
        // string rules
        Validator::RULE_ALPHA => 'Alpha',
        Validator::RULE_ALPHANUMERIC => 'AlphaNumeric',
        Validator::RULE_ALPHANUMHYPHEN => 'AlphaNumHyphen',
        Validator::RULE_LENGTH => 'Length',
        Validator::RULE_MAX_LENGTH => 'MaxLength',
        Validator::RULE_MIN_LENGTH => 'MinLength',
        Validator::RULE_FULLNAME => 'FullName',
        // array rules
        Validator::RULE_ARRAY_LENGTH => 'ArrayLength',
        Validator::RULE_ARRAY_MAX_LENGTH => 'ArrayMaxLength',
        Validator::RULE_ARRAY_MIN_LENGTH => 'ArrayMinLength',
        Validator::RULE_IN_LIST => 'InList',
        Validator::RULE_NOT_IN_LIST => 'NotInList',
        // date rules
        Validator::RULE_DATE => 'Date',
        Validator::RULE_DATETIME => 'DateTime',
        Validator::RULE_TIME => 'Time',
        // number rules
        Validator::RULE_BETWEEN => 'Between',
        Validator::RULE_GREATER_THAN => 'GreaterThan',
        Validator::RULE_LESS_THAN => 'LessThan',
        Validator::RULE_NUMBER => 'Number',
        Validator::RULE_INTEGER => 'Integer',
        // regular expression rules
        Validator::RULE_REGEX => 'Regex',
        Validator::RULE_NOT_REGEX => 'NotRegex',
        // other rules
        Validator::RULE_EMAIL => 'Email',
        Validator::RULE_EMAIL_DOMAIN => 'EmailDomanin',
        Validator::RULE_URL => 'Url',
        Validator::RULE_WEBSITE => 'Website',
        Validator::RULE_IP => 'IpAddress',
        'ipaddress' => 'IpAddress',
        Validator::RULE_MATCH => 'Match',
        Validator::RULE_EQUAL => 'Equal',
        // file rules
        Validator::RULE_FILE_EXTENSION => 'File\Extension',
        Validator::RULE_FILE_SIZE => 'File\Size',
        Validator::RULE_IMAGE => 'File\Image',
        Validator::RULE_IMAGE_WIDTH => 'File\ImageWidth',
        Validator::RULE_IMAGE_HEIGHT => 'File\ImageHeight',
        Validator::RULE_IMAGE_RATIO => 'File\ImageRatio',
        // upload rules
        Validator::RULE_UPLOAD_EXTENSION => 'Upload\Extension',
        Validator::RULE_UPLOAD_SIZE => 'Upload\Size',
        Validator::RULE_UPLOAD_IMAGE => 'Upload\Image',
        Validator::RULE_UPLOAD_IMAGE_WIDTH => 'Upload\ImageWidth',
        Validator::RULE_UPLOAD_IMAGE_HEIGHT => 'Upload\ImageHeight',
        Validator::RULE_UPLOAD_IMAGE_RATIO => 'Upload\ImageRatio',
        Validator::RULE_CALLBACK => 'Callback'
    );


    /**
     * Register a class to be used when creating validation rules
     *
     * @param string $name
     * @param string $class
     * @return \Sirius\Validation\RuleFactory
     */
    public function register($name, $class)
    {
        if (is_subclass_of($class, '\Sirius\Validation\Rule\AbstractValidator')) {
            $this->validatorsMap[$name] = $class;
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
     * @throws \InvalidArgumentException
     * @return \Sirius\Validation\Rule\AbstractValidator
     */
    public function createValidator($name, $options = null, $messageTemplate = null, $label = null)
    {
        $options = $this->normalizeOptions($options);

        $validator = $this->constructValidatorByNameAndOptions($name, $options);

        if (is_string($messageTemplate) && $messageTemplate !== '') {
            $validator->setMessageTemplate($messageTemplate);
        }
        if (is_string($label) && $label !== '') {
            $validator->setOption('label', $label);
        }
        return $validator;
    }

    /**
     * @param $options
     *
     * @return array|mixed
     */
    protected function normalizeOptions($options)
    {
        $result = $options;
        if ($options && is_string($options)) {
            $startChar = substr($options, 0, 1);
            if ($startChar == '{' || $startChar == '[') {
                $result = json_decode($options, true);
            } else {
                parse_str($options, $output);
                $result = $output;
            }
        } elseif (!$options) {
            $result = array();
        }

        if (!is_array($result)) {
            throw new \InvalidArgumentException('Validator options should be an array, JSON string or query string');
        }

        return $result;
    }

    /**
     * @param $name
     * @param $options
     *
     * @return CallbackRule
     */
    protected function constructValidatorByNameAndOptions($name, $options)
    {
        if (is_callable($name)) {
            $validator = new CallbackRule(
                array(
                    'callback'  => $name,
                    'arguments' => $options
                )
            );
        } else {
            $name = trim($name);
            // use the validator map
            if (isset($this->validatorsMap[ strtolower($name) ])) {
                $name = $this->validatorsMap[ strtolower($name) ];
            }
            // try if the validator is the name of a class in the package
            if (class_exists('\Sirius\Validation\Rule\\' . $name)) {
                $name = '\Sirius\Validation\Rule\\' . $name;
            }
            // at this point we should have a class that can be instanciated
            if (class_exists($name) && is_subclass_of($name, '\Sirius\Validation\Rule\AbstractValidator')) {
                $validator = new $name($options);
            }
        }

        if (!isset($validator)) {
            throw new \InvalidArgumentException(
                sprintf('Impossible to determine the validator based on the name: %s', (string)$name)
            );
        }
        return $validator;
    }

}
