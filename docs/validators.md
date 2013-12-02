# Validators classes

All validator classes exted the `AbstractValidator` class

## Usage

```php
use \Sirius\Validation\Validator\Length as LengthValidator;

// we'll use the Length class as an example to check if a string is between 5 and 255 characters long
$validator = new LengthValidator(array(
    'min' => 5,
	'max' => 255
));

// set/override options
$validator->setOption('min', 10);
$validator->setOption('max', 200);

// each class has constants for the options so your IDE be able to help you while coding
$validator->setOption(LengthValidator::OPTION_MIN, 10);
$validator->setOption(LengthValidator::OPTION_MAX, 100);

// set the error template message (overwrite the default)
$validator->setMessageTemplate('{label} must be between {min} and {max} characters long');

// add custom options that may be used by the error message
$validator->setOption('label', 'Full name');

// validate a value
$validator->validate('abc'); // this will return false
$validator->validate('abcdefghijlkmnop'); // this will return true;

// retrieve the error message (instance of `\Sirius\Validation\ErroMessage`)
$errorMessage = $validator->getMessage(); // 'Full name must be between 10 and 100 characters long'

// if you need to retrieve the potential error message 
// (ie: in case the validation will fail but before actually supplying a value)
// (eg: to send the potential error message to a client-side validation library)
$potentialMessage = $validator>getPotentialMessage(); // 'Full name must be between 10 and 100 characters long'
```

## Validators list

1. Alpha: values must contain only letters and spaces
2. AlphaNumeric: values must contain letters, numbers and spaces
3. AlphaNumHyphen: values must contain letters, numbers, dashes, underscores and spaces
4. ArrayLength: values which are arrays must contain a specific number of items. Validator options: `min` and `max`
5. ArrayMinlength: array must contain at least a specific number of items: Validator options: `min`
6. ArrayMinlength: array must contain at most a specific number of items: Validator options: `max`
7. Between: value must be a number between 2 limits: Validator options: `min` and `max`
8. LessThan: value must be less than a number. Validator options: `max` and `inclusive` (to determine if the comparator is < or <=, defaults to TRUE)
9. GreaterThan: value must be greater than a number. Validator options: `min` and `inclusive` (to determine if the comparator is > or >=, defaults to TRUE)
10. Email: value must be an email address. Uses a regular expression for validation
11. EmailDomain: the value, which should be an email address, must belong to a valid domain. Uses `checkdnsrr`
12. InList: the value must be in a list of acceptable values. Validator options: `list`
13. NotInList: the value must not be in a list of forbidden values: Validator options: `list`
14. Length: value, which should be a string, must a length which is between specified limits. Validator options: `min` and `max`
15. MinLength: string's length should be greater than a specified value. Validator options: `min`
16. MaxLength: string's length should be shorter than a specified value. Validator options: `max`
17. Required: value should not be `null` or an empty string
18. Regex: value must match a regular expression pattern.  Validator options: `pattern`
19. NotRegex: value must NOT match a regular expression pattern.  Validator options: `pattern`
20. Url: value must be a valid URL address (http, https, ftp etc)
21. Website: value must be a valid website address (http or https)
22. Callback: checks if a value is valid using a custom callback (a function, an object's method, a class' static method).  Validator options: `callback` and `arguments` (additional paramters for the callback)
23. Date: checks if a value in a date. Validator options: `format` (a PHP date format like `Y-m-d`).
24. DateTime: extends Date but the default format is `Y-m-d H:i:s`
25. Time: extends Date but the default format is `H:i:s`