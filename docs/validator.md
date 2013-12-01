#Validation\Validator

This is the class that will be instanciated to perform validation

```php
use Sirius\Validation\Validator;
$validator = new Validator($arrayContainingTheRules);
```

## Adding validation rules

```php
// syntax
$validator->add($selector, $name, $options = array(), $messageTemplate = null, $label = null);

// examples
$validator->add('username', 'required');
$validator->add('email', 'email', array(), 'Email address is not valid');
$validator->add('password', 'minLength', array('min' => 6), '{label} must have at least {min} characters', 'Password');
```

The <code>$name</code> must either
1. match an [individual validator class](validators.md). In this case the name can be the
	- the name of `Sirius\Validation\Validator` class (eg: `Email', 'MinLength')
	- the `strotolower` equivalent (eg: 'email', 'minlength')
	- a custom validator class that extends `Sirius\Vaidation\Validator\AbstractValidator` (eg: 'MyApp\Validation\Validator\Username')
2. or be a callable entity (function, object method or static method).

```php
$validator->add('username', 'MyClass::validateUsername', array(), 'Username is already taken');
```

The <code>$options</code> variable represents the configuration options for the validators or additional parameters for the callback.

The <code>$messageTemplate</code> is the message that will be associated with an item when the validation fails. 
If you don't provide it, each validator has it's own default error message.

The <code>$label</code> is the label associated with the field. 
From my experience, the most usefull error messages are those that contain the name of the field so I decided to make this option easily accessible.


### Shortcuts for adding rules

1. Add multiple rules at once by using just a string
```php
// separate rules using ' | ' (space/pipe/space)
$validator->add('email', 'required | email');
```

2. Add rule with parameters and custom messages using only a string
```php
$validator->add('name', 'minlength({"min":2})({label} must have at least {min} characters)(Name)');
// is similar to
$validator->add('name', 'minlength', array('min' => 2), '{label} must have at least {min} characters', 'Name');
```

3. Mix and match 1 and 2
```php
$validator->add('name', 'required | minlength({"min":2})({label} must have at least {min} characters)(Name)');
```

4. Add multiple rules at once
```php
$validator->add(array(
	'email' => 'required | email',
	'name' => 'required | minlength({"min":2})({label} must have at least {min} characters)(Name)'
));

## Validating data

```php
$validationResult = $validator->validate($_POST); // TRUE or FALSE
$messages = $validator->getMessages(); // array with all error messages
$emailErrorMessages = $validator->getMessages('email'); // error messages for the email address
```