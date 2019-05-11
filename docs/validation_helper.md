---
title: The validation helper
---

# The validation helper

This is a class which contains static methods to perform data validation. 
Is is designed to help you with basic validation operations, when you need to validate a single value or a small number of values

The Helper class uses the [Validators classes](validation_rules.md) for the validation.

```
use Latinosoft\Validation\Helper;

if (Helper::email('email@domain.com) && Helper::fullName('My Name')) {
	// proceed with your application logic here
}
```

It comes with a set of pre-set validation methods:

- email($value)
- integer($value)
- number($value) - integers and floats
- lessThan($value, $max) - $value is less than or equal to $max
- greaterThan($value, $min) - $value is greater or equal than $min
- between($value, $min, $max)
- exactly($value, $requirement)
- not($value, $requirement) - inverse of exactly()
- alpha($value) - string must contain only alphabetic characters
- alphanumeric($value) - letters and digits
- alphanumhyphen($value) - letters, digits, hyphen and underscore
- minLength($value, $min) - $value should be a string with at least $min characters
- maxLength($value, $max)
- length($value)
- setMinSize($value, $min) - $value should be an array with at least $min elements
- setMaxSize($value, $max)
- setSize($value, $min, $max)
- in($value, $listOfAllowedValues)
- notIn($value, $listOfDisallowedValues)
- equalTo($value, $element, $context) - $value should be equal to $context[$element]. Element can be something like addresses[0][state] though
- website($value) - $value should be a http(s) address
- url($value) - $value should be an URL (including FTP)
- ip($value)
- fullName($value) - $value should be a name (at least characters, first word not a letter, last word not a letter)
- date($value, $format) - $value should be a valid date provided in the $format format
- dateTime($value, $format)
- time($value, $time)

### Add your own validation methods

```php
use Latinosoft\ValidationHelper as ValidationHelper;
ValidationHelper::addMethod('username', 'UserLibrary::validateUsername');
// and call it latter
ValidationHelper::username('minime2013');
```