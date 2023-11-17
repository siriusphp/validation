<?php
declare(strict_types=1);

namespace Sirius\Validation\Rule;

use Sirius\Validation\DataWrapper\ArrayWrapper;
use Sirius\Validation\DataWrapper\WrapperInterface;
use Sirius\Validation\ErrorMessage;
use Sirius\Validation\Util\RuleHelper;

abstract class AbstractRule
{
    // default error message when there is no LABEL attached
    const MESSAGE = 'Value is not valid';

    // default error message when there is a LABEL attached
    const LABELED_MESSAGE = '{label} is not valid';

    /**
     * The validation context
     * This is the data set that the data being validated belongs to
     * @var WrapperInterface
     */
    protected ?WrapperInterface $context = null;

    /**
     * Options for the validator.
     * Also passed to the error message for customization.
     *
     * @var array<string,mixed>
     */
    protected array $options = [];

    /**
     * Custom error message template for the validator instance
     * If you don't agree with the default messages that were provided
     */
    protected string $messageTemplate;

    /**
     * Result of the last validation
     */
    protected bool $success = false;

    /**
     * Last value validated with the validator.
     * Stored in order to be passed to the errorMessage so that you get error
     * messages like '"abc" is not a valid email'
     */
    protected mixed $value = null;

    /**
     * The error message prototype that will be used to generate the error message
     */
    protected ?ErrorMessage $errorMessagePrototype = null;

    /**
     * Options map in case the options are passed as list instead of associative array
     *
     * @var array<int, string>
     */
    protected array $optionsIndexMap = [];

    /**
     * @param array<int|string, mixed>|string|null $options
     */
    public function __construct(mixed $options = null)
    {
        $options = RuleHelper::normalizeOptions($options, $this->optionsIndexMap);
        if (is_array($options) && !empty($options)) {
            foreach ($options as $k => $v) {
                $this->setOption((string) $k, $v);
            }
        }
    }

    /**
     * Generates a unique string to identify the validator.
     * It is used to compare 2 validators so you don't add the same rule twice in a validator object
     */
    public function getUniqueId(): string
    {
        return get_called_class() . '|' . json_encode(ksort($this->options));
    }

    /**
     * Set an option for the validator.
     *
     * The options are also be passed to the error message.
     */
    public function setOption(string $name, mixed $value): static
    {
        $this->options[$name] = $value;

        return $this;
    }

    /**
     * Get an option for the validator.
     *
     * @return mixed
     */
    public function getOption(string $name)
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
     * @param array<string, mixed>|WrapperInterface $context
     *
     * @throws \InvalidArgumentException
     */
    public function setContext(mixed $context = null): self
    {
        if ($context === null) {
            return $this;
        }
        if (is_array($context)) {
            $context = new ArrayWrapper($context);
        }
        if (!$context instanceof WrapperInterface) {
            throw new \InvalidArgumentException(
                'Validator context must be either an array or an instance
                of ' . WrapperInterface::class
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
     * @return AbstractRule
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
    public function getMessageTemplate(): string
    {
        if (isset($this->messageTemplate)) {
            return $this->messageTemplate;
        }
        if (isset($this->options['label'])) {
            return constant(get_class($this) . '::LABELED_MESSAGE');
        }

        return constant(get_class($this) . '::MESSAGE');
    }

    /**
     * Validates a value
     */
    abstract public function validate(mixed $value, string $valueIdentifier = null): bool;

    /**
     * Sets the error message prototype that will be used when
     * returning the error message if validation fails.
     * This option can be used when you need translation
     */
    public function setErrorMessagePrototype(ErrorMessage $errorMessagePrototype): self
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
    public function getErrorMessagePrototype(): ErrorMessage
    {
        if (!$this->errorMessagePrototype) {
            $this->errorMessagePrototype = new ErrorMessage();
        }

        return $this->errorMessagePrototype;
    }

    /**
     * Retrieve the error message if validation failed
     */
    public function getMessage(): ?ErrorMessage
    {
        if ($this->success) {
            return null;
        }
        $message = $this->getPotentialMessage();
        $message->setVariables([
            'value' => $this->value
        ]);

        return $message;
    }

    /**
     * Retrieve the potential error message.
     * Example: when you do client-side validation you need to access the "potential error message" to be displayed
     *
     * @return ErrorMessage
     */
    public function getPotentialMessage(): ErrorMessage
    {
        $message = clone $this->getErrorMessagePrototype();
        $message->setTemplate($this->getMessageTemplate());
        $message->setVariables($this->options);

        return $message;
    }

    /**
     * Method for determining the path to a related item.
     * Eg: for `lines[5][price]` the related item `lines[*][quantity]`
     * has the value identifier as `lines[5][quantity]`
     */
    protected function getRelatedValueIdentifier(string $valueIdentifier, string $relatedItem): string|null
    {
        // in case we don't have a related path
        if (!str_contains($relatedItem, '*')) {
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
        $relatedValueIdentifierParts = [];
        foreach ($relatedItemParts as $index => $part) {
            if ($part === '*' && isset($valueIdentifierParts[$index])) {
                $relatedValueIdentifierParts[] = $valueIdentifierParts[$index];
            } else {
                $relatedValueIdentifierParts[] = $part;
            }
        }

        $relatedValueIdentifier = implode('][', $relatedValueIdentifierParts) . ']';
        return str_replace(
            $relatedValueIdentifierParts[0] . ']',
            $relatedValueIdentifierParts[0],
            $relatedValueIdentifier
        );
    }
}
