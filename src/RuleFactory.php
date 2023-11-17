<?php
declare(strict_types=1);

namespace Sirius\Validation;

use Sirius\Validation\Rule\AbstractRule;
use Sirius\Validation\Rule\Callback as CallbackRule;
use Sirius\Validation\Rule\Matching;

class RuleFactory
{
    /**
     * Validator map allows for flexibility when creating a validation rule
     * You can use 'required' instead of 'required' for the name of the rule
     * or 'minLength'/'minlength' instead of 'MinLength'
     *
     * @var array<string,string>
     */
    protected array $validatorsMap = [];

    /**
     * @var array<string,string>
     */
    protected array $errorMessages = [];

    /**
     * @var array<string,string>
     */
    protected array $labeledErrorMessages = [];

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
    protected function registerDefaultRules(): void
    {
        $rulesClasses = [
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
            'MaxLength',
            'MinLength',
            'NotEqual',
            'NotInList',
            'NotMatch',
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
        ];
        foreach ($rulesClasses as $class) {
            $fullClassName = '\\' . __NAMESPACE__ . '\Rule\\' . $class;
            $name = strtolower(str_replace('\\', '', $class));
            $errorMessage = constant($fullClassName . '::MESSAGE');
            $labeledErrorMessage = constant($fullClassName . '::LABELED_MESSAGE');
            $this->register($name, $fullClassName, $errorMessage, $labeledErrorMessage);
        }
        $this->register('match', Matching::class, Matching::MESSAGE, Matching::LABELED_MESSAGE);
    }


    /**
     * Register a class to be used when creating validation rules
     *
     * @param string $name
     * @param string|class-string $class
     *
     * @return $this
     */
    public function register(string $name, string $class, string $errorMessage = '', string $labeledErrorMessage = ''): self
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
     * @param string|callable|mixed $name
     *            name of a validator class or a callable object/function
     * @param string|array<int|string,mixed>|null $options
     *            validator options (an array, JSON string or QUERY string)
     *
     * @return AbstractRule
     * @throws \InvalidArgumentException
     */
    public function createRule(mixed $name, mixed $options = null, string $messageTemplate = null, string $label = null): AbstractRule
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
     * @return $this
     */
    public function setMessages(string $rule, string $messageWithoutLabel = null, string $messageWithLabel = null)
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
     */
    protected function getSuggestedMessageTemplate(string $name, bool $withLabel): ?string
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
     * @param string|array<int|string, mixed>|null $options
     *
     * @return CallbackRule|AbstractRule
     */
    protected function construcRuleByNameAndOptions(string $name, mixed $options = null)
    {
        if (is_callable($name)) {
            $validator = new CallbackRule([
                'callback' => $name,
                'arguments' => $options
            ]);
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
                sprintf('Impossible to determine the validator based on the name: %s', (string)$name)
            );
        }

        return $validator;
    }
}
