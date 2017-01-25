#Sirius Validation

[![Source Code](http://img.shields.io/badge/source-siriusphp/validation-blue.svg?style=flat-square)](https://github.com/siriusphp/validation)
[![Latest Version](https://img.shields.io/packagist/v/siriusphp/validation.svg?style=flat-square)](https://github.com/siriusphp/validation/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://github.com/siriusphp/validation/blob/master/LICENSE)
[![Build Status](https://img.shields.io/travis/siriusphp/validation/master.svg?style=flat-square)](https://travis-ci.org/siriusphp/validation)
[![PHP 7 ready](http://php7ready.timesplinter.ch/siriusphp/validation/master/badge.svg)](https://travis-ci.org/siriusphp/validation)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/siriusphp/validation.svg?style=flat-square)](https://scrutinizer-ci.com/g/siriusphp/validation/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/siriusphp/validation.svg?style=flat-square)](https://scrutinizer-ci.com/g/siriusphp/validation)
[![Total Downloads](https://img.shields.io/packagist/dt/siriusphp/validation.svg?style=flat-square)](https://packagist.org/packages/siriusphp/validation)

Sirius Validation is a library for data validation. It offers:

1. [validator object](docs/validator.md)
2. [45 build-in validation rules](docs/validation_rules.md). There are validators for strings, array, numbers, emails, URLs, files and uploads
3. [validation helper](docs/helper.md) to simplify the validation of single values

Out-of-the-box, the library can handle `array`s, `ArrayObject`s and objects that have implemented the `toArray` method.
In order to validate other data containers you must create a [`DataWrapper`](https://github.com/siriusphp/validation/blob/master/src/Validation/DataWrapper/WrapperInterface.php) so that the validator be able to extract data from your object.

##Elevator pitch

```php
$validator = new \Sirius\Validation\Validator;

// add a validation rule
$validator->add('title', 'required');

// add a rule that has a list of options
$validator->add('title', 'length', array('min' => 10, 'max' => 100));
// or use JSON
$validator->add('title', 'length', '{"min": 10, "max": 100}');
// or a URL query string
$validator->add('title', 'length', 'min=10&max=100');
// or, if you know that the validator can CORECTLY parse (ie: understand) the options string
$validator->add('title', 'length', '10,100');

// add a rule with a custom error message
$validator->add('title', 'maxlength', 'max=100', 'Article title must have less than {max} characters');

// add a rule with a custom message and a label (very handy with forms)
$validator->add('title:Title', 'maxlength', 'max=100', '{label} must have less than {max} characters');

// add all of rule's configuration in a string (you'll see later why it's handy')
$validator->add('title:Title', 'maxlength(max=255)({label} must have less than {max} characters)');

// add multiple rules at once (separate using [space][pipe][space])
$validator->add('title:Title', 'required | maxlength(255) | minlength(min=10)');

// add all your rules at once
$validator->add(array(
    'title:Title' => 'required | maxlength(100)({label} must have less than {max} characters)',
	'content:Content' => 'required',
	'source:Source' => 'website'
));

// add nested rules
$validator->add('recipients[*]:Recipients', 'email'); //all recipients must be valid email addresses
$validator->add('shipping_address[city]:City', 'MyApp\Validator\City'); // uses a custom validator to validate the shipping city

```

##Links

- [documentation](http://sirius.ro/php/sirius/validation/)
- [changelog](CHANGELOG.md)

##Known issues

In PHP 5.3 there is some problem with the SplObject storage that prevents the library to remove validation rules.
This means that in PHP 5.3, you cannot remove a validation rule from a `Validator` or `ValueValidator` object

