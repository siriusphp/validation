# ErrorMessage

The [validator object](validator.md) does not return the validation errors as strings but as instances of the `Sirius\Validation\ErrorMessage` class.

The reasons for this are:

1. most validators have parameters that influence the error message (eg: a validator that requires the value to be a string longer than 10 characters)
2. some times you need to translate the error messages and each app may have its own way of translating strings.
3. we don't know if the translation is done when the validator is constructed or wait until it is displayed to the user

The `ErrorMessage` class implements the `__toString` method so you can echo them out of the box.

The error message has

1. a `template`
2. a set of `variables` which consists of the validator's arguments (eg: 'min' => 10) and anything else you want

The variables are 'injected' into the resulting string through `str_replace`-ing the variables name inside brackets with their values (eg: `{min}` is replaced by `10`);

## Translating the error messages

There are a couple of ways to translate the error messages

##### 1.Postpone the translation until display

```php
// make a function especially for that
echo translateErrorMessageObjects($validator->getMessage('title'));
```

##### 2. Use a translatable error message prototype on validators

```php
class TranslatableErrorMessage extends Sirius\Validation\ErrorMessage {
	function __toString() {
		// write your implementation here
	}
}

// later when constructing your validators

$validator = new Sirius\Validation\Validator(null, new TranslatableErrorMessage);
```

##### 3.Use translated strings

```php
// in the validator
$validator->add('title', 'maxlength', 'max=100', __('{label} must have less than {max} characters'), __('Title'));

// or the rule factory
$ruleFactoryInstance->setErrorMessages('maxlength', __('This field must have less than {max} characters'), __('{label} must have less than {max} characters'));
```
