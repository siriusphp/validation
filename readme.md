#Sirius Validation

[![Build Status](https://scrutinizer-ci.com/g/siriusphp/validation/badges/build.png?b=master)](https://scrutinizer-ci.com/g/siriusphp/validation/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/siriusphp/validation/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/siriusphp/validation/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/siriusphp/validation/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/siriusphp/validation/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/siriusphp/validation/v/stable.png)](https://packagist.org/packages/siriusphp/validation)
[![License](https://poser.pugx.org/siriusphp/validation/license.png)](https://packagist.org/packages/siriusphp/validation)

Sirius Validation is a library for data validation. It offers:

1. [validator object](docs/validator.md) to validate arrays, `ArrayObjects` or objects that have a `toArray` method. It can be extended easily to validate other types.
2. [value validator object](docs/value_validator.md) to validate single values
2. [validation helper](docs/helper.md) to simplify single value validation (does not generate error messages, only returns TRUE/FALSE)
3. [build-in validation rules](docs/rules.md) to perform the actual data validation. The validation rules are used by the helper and validator objects.

Out-of-the-box, the library can handle `array`s, `ArrayObject`s and objects that have implemented the `toArray` method.
In order to validate other data containers you must create a [`DataWrapper`](https://github.com/siriusphp/validation/blob/master/src/Validation/DataWrapper/WrapperInterface.php) so that the validator be able to extract data from your object.

##Elevator pitch

```php
$validation = new \Sirius\Validation\Validator;

// add a validation rule
$validator->add('title', 'required');

// add a rule that has a list of options
$validator->add('title', 'maxlength', array('max' => 100));
// or use JSON
$validator->add('title', 'maxlength', '{"max": 100}');
// or a URL query string
$validator->add('title', 'maxlength', 'max=100');

// add a rule with a custom error message
$validator->add('title', 'maxlength', 'max=100', 'Article title must have less than {max} characters');

// add a rule with a custom message and a label (very handy with forms)
$validator->add('title', 'maxlength', 'max=100', '{label} must have less than {max} characters', 'Title');

// add all of rule's configuration in a string (you'll see later why it's handy')
$validator->add('title', 'maxlength(max=255)({label} must have less than {max} characters)(Title)');

// add multiple rules at once (separate using [space][pipe][space])
$validator->add('title', 'required | maxlength(max=255) | minlength(min=10)');

// add all your rules at once
$validator->add(array(
    'title' => 'required | maxlength(max=10)({label} must have less than {max} characters)(Title)',
	'content' => 'required',
	'source' => 'website'
));

// add nested rules
$validator->add('recipients[*]', 'email'); //all recipients must be valid email addresses
$validator->add('shipping_address[city]', 'MyApp\Validator\City'); // uses a custom validator to validate the shipping city

```

##Documentation

[go to the documentation](docs/index.md)

##Known issues

In PHP 5.3 there is some problem with the SplObject storage that prevents the library to remove validation rules.
This means that in PHP 5.3, you cannot remove a validation rule from a `Validator` or `ValueValidator` object

## Release notes

#### 1.2.2

- fixed bug with the 'between' validator (pull request #16)
- added support for PHP 5.3 (tests fail but due to the short array syntax, not the inherent code in the library)
- improved documentation on how to use complex validators (ie: that have dependencies)

#### 1.2.1

- fixed bug with the required rule not working properly on empty strings

#### 1.2.0

- improved the code base on Scrutinizer CI suggestions
- removed the ValidatableTrait trait

#### 1.1

- Added HHVM to Travis CI
- Renamed Validator\* classes into Rule\* classes (breaking change if you used custom rule classes)
- Renamed ValidatorFactory to RuleFactory (breaking change)
