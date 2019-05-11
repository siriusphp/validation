---
title: The validation error messages
---

# The validation error messages

The [validator object](validator.md) does not return the validation errors as strings but as instances of the `Latinosoft\Validation\ErrorMessage` class.

The reasons for this are:

1. most validators have parameters that influence the error message (eg: a validator that requires the value to be a string longer than 10 characters)
2. sometimes you need to translate the error messages and each app may have its own way of translating strings.
3. we don't know if the translation is done when the validator is constructed or wait until it is displayed to the user

The `ErrorMessage` class implements the `__toString` method so you can echo them out of the box.

The error message has

1. a `template`
2. a set of `variables` which consists of the rule's arguments (eg: 'min' => 10) and anything else you want

The variables are 'injected' into the resulting string through `str_replace`-ing the variables name inside brackets with their values (eg: `{min}` is replaced by `10`);

The Latinosoft\Validation library doesn't use a specific implementation for translating the error messages but we have a [recipe](translate_messages.md) for that.