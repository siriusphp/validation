# Validation rules classes

All validator classes exted the `AbstractValidator` class

## Usage

```php
use \Sirius\Validation\Rule\Length as LengthRule;

// we'll use the Length class as an example to check if a string is between 5 and 255 characters long
$validator = new LengthRule(array(
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
$validator->validate('abcdefghijlkmnop'); // this will return true

// retrieve the error message (instance of `\Sirius\Validation\ErroMessage` which implements toString())
$errorMessage = $validator->getMessage(); // if echo-ed will output 'Full name must be between 10 and 100 characters long'

// if you need to retrieve the potential error message 
// (ie: in case the validation will fail but before actually supplying a value)
// (eg: to send the potential error message to a client-side validation library)
$potentialMessage = $validator>getPotentialMessage(); // 'Full name must be between 10 and 100 characters long'
```

## Validators list

### Required validators
1. `Required`: value should not be `null` or an empty string
2. `RequiredWith`: value is required when another item is present in the context. Rule options: `item`
3. `RequiredWithout`: value is required when another item is *not* present in the context. Rule options: `item`
4. `RequiredWhen`: value is required when another item matches another rule. Rule options: `item`, `rule` (class name of a validator), `options` (options for the rule)

### String validators
1. `Alpha`: values must contain only letters and spaces
2. `AlphaNumeric`: values must contain letters, numbers and spaces
3. `AlphaNumHyphen`: values must contain letters, numbers, dashes, underscores and spaces
4. `Length`: value, which should be a string, must a length which is between specified limits. Rule options: `min` and `max`
5. `MinLength`: string's length should be greater than a specified value. Rule options: `min`
6. `MaxLength`: string's length should be shorter than a specified value. Rule options: `max`
7. `FullName`: a string should represent a full name (at least 6 characters, at least 2 word, each word at least 2 characters long)

### Array validators
1. `ArrayLength`: values which are arrays must contain a specific number of items. Rule options: `min` and `max`
2. `ArrayMinlength`: array must contain at least a specific number of items: Rule options: `min`
3. `ArrayMinlength`: array must contain at most a specific number of items: Rule options: `max`
4. `InList: the value must be in a list of acceptable values. Rule options: `list`
5. `NotInList: the value must not be in a list of forbidden values: Rule options: `list`

### Number validators
7. `Between`: value must be a number between 2 limits: Rule options: `min` and `max`
8. `LessThan`: value must be less than a number. Rule options: `max` and `inclusive` (to determine if the comparator is < or <=, defaults to TRUE)
9. `GreaterThan`: value must be greater than a number. Rule options: `min` and `inclusive` (to determine if the comparator is > or >=, defaults to TRUE)

### Email/URLs validators
1. `Email`: value must be an email address. Uses a regular expression for validation
2. `EmailDomain`: the value, which should be an email address, must belong to a valid domain. Uses `checkdnsrr`
3. `Url`: value must be a valid URL address (http, https, ftp etc)
4. `Website`: value must be a valid website address (http or https)

### Date/Time validators
1. `Date`: checks if a value in a date. Rule options: `format` (a PHP date format like `Y-m-d`).
2. `DateTime`: extends Date but the default format is `Y-m-d H:i:s`
3. `Time`: extends Date but the default format is `H:i:s`

### Other validators
1. `Regex`: value must match a regular expression pattern.  Rule options: `pattern`
2. `NotRegex`: value must NOT match a regular expression pattern.  Rule options: `pattern`
3. `Callback`: checks if a value is valid using a custom callback (a function, an object's method, a class' static method).  Rule options: `callback` and `arguments` (additional paramters for the callback)
4. `Match`: the value must match the value of another item in the context. Rule options: `item` (eg: if `auth[password_confirm]` must match `auth[password]` the `item` is `auth[password]`
5. `Equal`: the value must be the same as predefined value. Rule options: `value`

### File validators
File validators work only with local files and they fail if the file does not exist
1. `File\Extension`. Checks if the file has a certain extension. Rule options: `allowed` which can be an array or a comma separated string.
2. `File\Image`. Checks if the file is an image of a certain type. Rule options: `allowed` which can be an array or a comma separated string (default: `jpg,png,gif`)
3. `File\ImageRatio`. Checks if the image has a certain ratio. Rule options: `ratio` which can be a number or a string like `4:3`, `error_margin` - how much the file's ratio can deviate from the target (default: 0)
4. `File\ImageWidth`. Checks if the image's width is between certain limits. Rule options: `min` (default: 0) and `max` (default: 1 million)
5. `File\ImageHeight`. Checks if the image's height is between certain limits. Rule options: `min` (default: 0) and `max` (default: 1 million)
6. `File\Size`. Checks if the file' size is bellow a certain limit. Rule options: `size` which can be a number or a string like '10K', '0.5M' or '1.3G` (default: 2M)

### Upload validators
Upload validators work only uploaded files (each file is an upload-like array) and they fail if the temporary file does not exist.
1. `Upload\Extension`. Checks if the uploaded file has a certain extension. Rule options: `allowed` which can be an array or a comma separated string.
2. `Upload\Image`. Checks if the uploaded file is an image of a certain type. Rule options: `allowed` which can be an array or a comma separated string (default: `jpg,png,gif`)
3. `Upload\ImageRatio`. Checks if the uploaded image has a certain ratio. Rule options: `ratio` which can be a number or a string like `4:3`, `error_margin` - how much the file's ratio can deviate from the target (default: 0)
4. `Upload\ImageWidth`. Checks if the uploaded image's width is between certain limits. Rule options: `min` (default: 0) and `max` (default: 1 million)
5. `Upload\ImageHeight`. Checks if the uploaded image's height is between certain limits. Rule options: `min` (default: 0) and `max` (default: 1 million)
6. `Upload\Size`. Checks if the uploaded file' size is bellow a certain limit. Rule options: `size` which can be a number or a string like '10K', '0.5M' or '1.3G` (default: 2M)

*Note!* The upload validators use only the `tmp_name` and `name` values to perform the validation