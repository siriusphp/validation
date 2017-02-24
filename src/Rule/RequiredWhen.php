<?php
namespace Sirius\Validation\Rule;

class RequiredWhen extends Required
{
    const OPTION_ITEM = 'item';
    const OPTION_RULE = 'rule';
    const OPTION_RULE_OPTIONS = 'rule_options';

    const MESSAGE = 'This field is required';
    const LABELED_MESSAGE = '{label} is required';

    public function getItemRule()
    {
        /* @var $rule AbstractValidator */
        $rule        = false;
        $ruleOptions = (isset($this->options[self::OPTION_RULE_OPTIONS])) ?
            (array) $this->options[self::OPTION_RULE_OPTIONS] :
            array();

        if (is_string($this->options[self::OPTION_RULE])) {
            $ruleClass = $this->options[self::OPTION_RULE];
            if (class_exists($ruleClass)) {
                $rule = new $ruleClass($ruleOptions);
            } elseif (class_exists('Sirius\\Validation\\Rule\\' . $ruleClass)) {
                $ruleClass = 'Sirius\\Validation\\Rule\\' . $ruleClass;
                $rule      = new $ruleClass($ruleOptions);
            }
        } elseif (is_object($this->options[self::OPTION_RULE])
                  && $this->options[self::OPTION_RULE] instanceof AbstractRule
        ) {
            $rule = $this->options[self::OPTION_RULE];
        }
        if (! $rule) {
            throw new \InvalidArgumentException(
                'Validator for the other item is not valid or cannot be constructed based on the data provided'
            );
        }
        $context = $this->context ? $this->context : array();
        $rule->setContext($context);

        return $rule;
    }

    public function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;

        if (! isset($this->options[self::OPTION_ITEM])) {
            $this->success = true;
        } else {
            $relatedItemPath  = $this->getRelatedValueIdentifier($valueIdentifier, $this->options[self::OPTION_ITEM]);
            $relatedItemValue = $relatedItemPath !== null ? $this->context->getItemValue($relatedItemPath) : null;

            $itemRule = $this->getItemRule();
            if ($itemRule->validate($relatedItemValue, $relatedItemPath)) {
                $this->success = ($value !== null && trim($value) !== '');
            } else {
                $this->success = true;
            }
        }

        return $this->success;
    }
}
