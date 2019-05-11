---
title: The validator object
---

# The validator object

This is the class that will be instantiated to perform validation

```php
use Latinosoft\Validation\RuleFactory;
use Latinosoft\Validation\ErrorMessage;
use Latinosoft\Validation\Validator;

$ruleFactory = new RuleFactory;
$errorMessagePrototype = new ErrorMessage;
$validator = new Validator($ruleFactory, $errorMessagePrototype);
```

`$validatorFactory` and `$errorMessagePrototype` are optional, they have a default value.
See [RuleFactory](rule_factory.md) and [ErrorMessage](error_message.md) for details

## Add validation rules

These are just instructions for the [RuleFactory](rule_factory.md) to create the actual rules

```php
// syntax
$validator->add($selector, $name = null, $options = null, $messageTemplate = null, $label = null);

// examples
$validator->add('username', 'required');
$validator->add('password', 'minLength', array('min' => 6), '{label} must have at least {min} characters', 'Password');
$validator->add('additional_emails[*]', 'email', array(), 'Email address is not valid');
```

Be sure to check the [syntactic sugar options](syntactic_sugar.md) to reduce the verbosity.

##### $selector

Is the path to the value(s) that will be validated with the rule

##### $name

The <code>$name</code> must either:
1. the name of a rule registered with the [`RuleFactory`](rule_factory.md)
2. the name of a class within the `Latinosoft\Validation\Rule` namespace (eg: `Email', 'MinLength') 
3. the name of a class that extends the `Latinosoft\Vaidation\Rule\AbstractRule` class
4. a callable entity (function, object method or static method) (eg: `$validator->add('username', 'MyClass::validateUsername', null, 'Username is already taken')`).

##### $options
The <code>$options</code> variable represents the configuration options for the validators or additional parameters for the callback. It can be:

1. an array
2. a JSON string: `{"min": 100, "max": 200}`
3. a URL query string: `min=100&max=200`
4. a CSV string: `100,200` (this requires the validation rule class has the `optionsIndexMap` array properly set up)


##### $messageTemplate
The <code>$messageTemplate</code> is the message that will be associated with an item when the validation fails. 
Each validator has it's own default error message so you don't have to provide it.

##### $label
The <code>$label</code> is the label associated with the field. 
The most useful error messages are those that contain the name of the field so this will come very handy.


## Validate data

```php
$validationResult = $validator->validate($_POST); // TRUE or FALSE
$messages = $validator->getMessages(); // array with all error messages
$emailErrorMessages = $validator->getMessages('email'); // error messages for the email address
```

If for whatever reason you need to manually set error messages you can do it like so
```php
$validator->addMessage('email', 'This value should be an email address');
```
and clear them
```php
$validator->clearMessages();
```

Anytime you execute `$validator->validate($values)` the validation messages are cleared (even those set manually).
