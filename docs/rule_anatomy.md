---
title: The anatomy of a validation rule
---

# The anatomy of a validation rule

The classes for validation rules extend the `Latinosoft\Validation\Rule\AbstractRule` class

The class has the following constants:

- `MESSAGE` which is the default message to be shown in case of an error when there is not label associated with the value to be validated.
- `LABELED_MESSAGE` which is use when there is a label associated with the field to be validated

These constants are here so that the validator can return an error message without you having to specify one (see the `messageTemplate` property below).

The class has the following properties

- `options` - the parameters required during validation. In the case of a string length rule the options are `min` (minimum numbers of characters)  and `max` (maximum number of characters)
- `messageTemplate` - the string that will determine the error message that will be returned if the value is not valid and you don't want to use the default messages
- `value` - the last value that was validated by the rule
- `context` - the data set that is being validated (eg: $_POST in case of a form). The `context` must implement the `Latinosoft\Validation\DataWrapper\WrapperInterface`


## Usage

The operations below are performed by the [RuleFactory](rule_factory.md) but they are here to help you understand the process.

### Initialize the validation rule

```php
use \Latinosoft\Validation\Rule\Length as LengthRule;

// we'll use the Length class as an example to check if a string is between 5 and 255 characters long
$validator = new LengthRule(array(
    'min' => 5,
    'max' => 255
));

// or use the power of your IDE to reduce the chances for mistakes
$validator = new LengthRule(array(
    LengthRule::OPTION_MIN => 5,
    LengthRule::OPTION_MAX => 255
));

// you can also pass it any string that the class knows how to normalize (see the normalizeOptions() method)
$validator = new LengthRule('min=5&max=255');

```

### You can overwrite the options

```php
// set/override options
$validator->setOption('min', 10);
$validator->setOption('max', 200);
```

### Set a custom error message if you don't like the default

```php
// set the error template message (overwrite the default)
$validator->setMessageTemplate('{label} must be between {min} and {max} characters long');

// add custom options that may be used by the error message
$validator->setOption('label', 'Full name');
```

### Run the validation rule against a value

```php
// validate a value without a context
$validator->validate('abc'); // this will return false

// or within a context
$validator->validate('abcdefghijlkmnop', $context); // this will return true
```

### Get the error message (actual or potential)

```
// retrieve the error message (instance of `\Latinosoft\Validation\ErroMessage` which implements toString())
$errorMessage = $validator->getMessage(); // if echo-ed will output 'Full name must be between 10 and 100 characters long'

// if you need to retrieve the potential error message 
// (eg: to send the potential error message to a client-side validation library)
$potentialMessage = $validator>getPotentialMessage(); // 'Full name must be between 10 and 100 characters long'
```

