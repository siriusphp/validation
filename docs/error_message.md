# ErrorMessage

The [validator object](validator.md) does not return the validation errors as strings but as instances of the `Sirius\Validation\ErrorMessage` class.

The reasons for this are:

1. most validators have paramters that influence the error message (eg: a validator that requires the value to be a string longer than 10 characters)
2. some times you need to translate the error messages and each app may have their own way of translating strings.
3. we don't know if the translation is done when the validator is constructed or wait until it is echoed

You can do either
```php
$validator->add('title', 'maxlength', 'max=100', translate('{label} must have less than {max} characters'), translate('Title'));
```

or
```php
echo translateErrorMessageObjects($validator->getMessage('title');
```

The `ErrorMessage` class implements the `__toString` method so you can echo them out of the box.

The error message has

1. a `template`
2. a set of `variables` which consists of the validator's arguments (eg: 'min' => 10) and anything else you want

The variables are 'injected' into the resulting string through searching and replacing the variables name inside brackets with their values (eg: `{min}` is replaced by `10`);

