<?php
declare(strict_types=1);

namespace Sirius\Validation;

use Sirius\Validation\Rule\AbstractRule;
use Sirius\Validation\Rule\AbstractValidator;

class ValueValidator
{

    /**
     * The error messages generated after validation or set manually
     *
     * @var array<string,mixed>
     */
    protected $messages = [];

    protected RuleFactory $ruleFactory;

    protected ErrorMessage $errorMessagePrototype;

    protected RuleCollection $rules;

    protected string $label;

    public function __construct(
        RuleFactory  $ruleFactory = null,
        ErrorMessage $errorMessagePrototype = null,
        string       $label = ''
    )
    {
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

    public function setLabel(string $label = ''): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Add 1 or more validation rules
     *
     * @param string|callable $name
     * @param string|array<string,mixed>|null $options
     * @param string|null $messageTemplate
     * @param string|null $label
     *
     * @return ValueValidator
     * @example
     * // add multiple rules at once
     * $validator->add(array(
     *   'required',
     *   ['required', ['email', null, '{label} must be an email', 'Field B']],
     * ));
     *
     * // add multiple rules using a string
     * $validator->add('required | email');
     *
     * // add validator with options
     * $validator->add('minlength', ['min' => 2], '{label} should have at least {min} characters', 'Field label');
     *
     * // add validator with string and parameters as JSON string
     * $validator->add('minlength({"min": 2})({label} should have at least {min} characters)(Field label)');
     *
     * // add validator with string and parameters as query string
     * $validator->add('minlength(min=2)({label} should have at least {min} characters)(Field label)');
     *
     */
    public function add(mixed $name, $options = null, string $messageTemplate = null, string $label = null): self
    {
        if (is_array($name)) {
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
        if (!$label && isset($this->label)) {
            $label = $this->label;
        }

        $validator = $this->ruleFactory->createRule($name, $options, $messageTemplate, $label);

        return $this->addRule($validator);
    }

    /**
     * @param array<int,mixed> $rules
     */
    public function addMultiple($rules): self
    {
        foreach ($rules as $singleRule) {
            // make sure the rule is an array (the parameters of subsequent calls);
            $singleRule = is_array($singleRule) ? $singleRule : [$singleRule];
            call_user_func_array([$this, 'add'], $singleRule);
        }

        return $this;
    }

    public function addRule(AbstractRule $validationRule): self
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
     * @param string|array<string, mixed> $options
     *            rule options, necessary for rules that depend on params for their ID
     *
     * @return self
     * @throws \InvalidArgumentException
     * @internal param string $selector data selector
     */
    public function remove($name = true, $options = null): self
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
     * @param string $ruleAsString
     *
     * @return array<int,mixed>
     * @example 'minLength({"min":2})({label} must have at least {min} characters)(Street)'
     *
     *          will be converted into
     *
     *          [
     *            'minLength', // validator name
     *            ['min' => 2'], // validator options
     *            '{label} must have at least {min} characters',
     *            'Street' // label
     *          ]
     *
     */
    protected function parseRule($ruleAsString): array
    {
        $ruleAsString = trim($ruleAsString);
        $name = $ruleAsString;
        $options = [];
        $messageTemplate = null;
        $label = null;

        if (str_contains($ruleAsString, '(')) {
            $name = substr($ruleAsString, 0, strpos($ruleAsString, '(')); //@phpstan-ignore-line
            $ruleAsString = substr($ruleAsString, strpos($ruleAsString, '(')); //@phpstan-ignore-line
            $matches = [];
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
        }

        return [
            $name,
            $options,
            $messageTemplate,
            $label
        ];
    }


    /**
     * @param DataWrapper\WrapperInterface|null $context
     */
    public function validate(mixed $value, string $valueIdentifier = '', DataWrapper\WrapperInterface $context = null): bool
    {
        $this->messages = [];
        $isRequired = false;

        // evaluate the required rules
        /** @var AbstractRule $rule */
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
        /** @var AbstractRule $rule */
        foreach ($this->rules as $rule) {
            if (!($rule instanceof Rule\Required)) {
                $this->validateRule($rule, $value, $valueIdentifier, $context);

                // if field is required and we have an error,
                // do not continue with the rest of rules
                if ($isRequired && count($this->messages)) { //@phpstan-ignore-line
                    break;
                }
            }
        }

        return count($this->messages) === 0;
    }

    /**
     * @param DataWrapper\WrapperInterface|null $context
     */
    private function validateRule(AbstractRule $rule, mixed $value, string $valueIdentifier, $context): bool
    {
        $rule->setContext($context);
        if (!$rule->validate($value, $valueIdentifier)) {
            $this->addMessage($rule->getMessage());
            return false;
        }
        return true;
    }

    /**
     * @return mixed[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    public function addMessage(mixed $message): ValueValidator
    {
        array_push($this->messages, $message);

        return $this;
    }

    public function getRules(): RuleCollection
    {
        return $this->rules;
    }

    /**
     * @param mixed $value
     */
    protected function isEmpty($value): bool
    {
        return in_array($value, [null, ''], true);
    }
}
