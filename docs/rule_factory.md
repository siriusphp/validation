---
title: The validation rules factory
---

# The validation rules factory

Every [validator object](validator.md) uses a `RuleFactory` to construct the validation rules based on the parameters received by the `add()` method.

The `RuleFactory` is a simple registry-like object which stores

1. the names of validation rules and their corresponding [validation rules classes](rules.md).
2. the default error messages for each validation rule. These are used when there is NO label attached to the field
3. the default **LABELED** error messages for each validation rule. These are used when the field has a label attached

By configuring the default error messages in the `RuleFactory` you don't have to repeat yourself. Since you should use a single `RuleFactory` for the entire app, there will be a single place where you can set the error message for, let's say, the `required` rule.

**!!! Important !!!** The error messages are optional because each [validation rule class](rule_anatomy.md) has constants which define the default error messages for that class.

### Registering validator classes

The code below assumes you got a handle of the $validationFactory from a registry or dependency injection container.

```
use Latinosoft\Validation\RuleFactory;

$ruleFactory = new RuleFactory;
// register new validation class
$ruleFactory->register('user_exists', 'MyApp\UserExistsValidator', $defaultMessageWithoutLabel, $defaultMessageWithLabel);

// overwrite an already registered class
$ruleFactory->register('email', 'MyApp\VeryPowerfullEmailValidator', $defaultMessageWithoutLabel, $defaultMessageWithLabel);
```

### Overwrite the error messages at any time

```php
// override just the error messages
$ruleFactory->setMessages('email', $defaultMessageWithoutLabel, $defaultMessageWithLabel);
// obviously you can override only what you need
$ruleFactory->setMessages('email', null, $defaultMessageWithLabel);
```

### Inject the RuleFactory into the Validator

If you omit this step you will end up using a regular (ie: non-custom) `RuleFactory` instance

```php
use Latinosoft\Validation\Validator;

$validator = new Validator($ruleFactory);
```

