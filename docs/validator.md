# Using the Validator object

This is the class that will be instanciated to perform validation

```php
use Sirius\Validation\RuleFactory;
use Sirius\Validation\ErrorMessage;
use Sirius\Validation\Validator;

$ruleFactory = new RuleFactory;
$errorMessagePrototype = new ErrorMessage;
$validator = new Validator($validatorFactory, $errorMessagePrototype);
```

`$validatorFactory` and `$errorMessagePrototype` are optional dependencies (ie: they have a default value).
See [RuleFactory](rule_factory.md) and [ErrorMessage](error_message.md) for details

## Adding validation rules

```php
// syntax
$validator->add($selector, $name = null, $options = null, $messageTemplate = null, $label = null);

// examples
$validator->add('username', 'required');
$validator->add('password', 'minLength', array('min' => 6), '{label} must have at least {min} characters', 'Password');
$validator->add('additional_emails[*]', 'email', array(), 'Email address is not valid');
```

Validation rules are created by the [ValidatorFactory](validator_factory.md)

### Parameters:

#### $name
The <code>$name</code> must either:

1. match an [individual validation rule class](rules.md). In this case the name can be the
    - the name of `Sirius\Validation\Rule` class (eg: `Email', 'MinLength')
	- a custom validator class that extends `Sirius\Vaidation\Rule\AbstractValidator` (eg: 'MyApp\Validation\Rule\Username')
    - a name of a registered validator using `$validator->getValidatorFactory()->register('email', 'MyApp\ValidationRule\MorePowerfullEmailValidator')`;
2. or be a callable entity (function, object method or static method).

```php
$validator->add('username', 'MyClass::validateUsername', null, 'Username is already taken');
```

#### $options
The <code>$options</code> variable represents the configuration options for the validators or additional parameters for the callback. It can be:

1. an array
2. a JSON string: `{"min": 100, "max": 200}`
3. a URL query string: `min=100&max=200`


#### $messageTemplate
The <code>$messageTemplate</code> is the message that will be associated with an item when the validation fails. 
Each validator has it's own default error message so you don't have to provide it.

#### $label
The <code>$label</code> is the label associated with the field. 
From my experience, the most usefull error messages are those that contain the name of the field so I decided to make this option easily accessible.


### Syntactic sugar

#### 1. Add multiple rules at once by using just a string
```php
// separate rules using ' | ' (space, pipe, space)
$validator->add('email', 'required | email');
```

#### 2. Add rule with parameters and custom messages using only a string
```php
// parameters set as JSON string
$validator->add('name', 'minlength({"min":2})({label} must have at least {min} characters)(Name)');
// or parameters set as query string
$validator->add('name', 'minlength(min=2)({label} must have at least {min} characters)(Name)');
// the above examples are similar to
$validator->add('name', 'minlength', array('min' => 2), '{label} must have at least {min} characters', 'Name');
```

#### 3. Mix and match 1 and 2
```php
$validator->add('name', 'required | minlength({"min":2})({label} must have at least {min} characters)(Name)');
```

Of course this means the error message cannot contain the ` | ` sequence

#### 4. Add multiple rules per value
```php
$validator->add('email', array(
    // only through the name of the validation rule
    'email',
    // or with all parameters
    array('minlength', 'min=2', '{label} must have at least {min} characters', 'Email'),
    // or as a shortcurt
    'minlength(min=2)({label} must have at least {min} characters)(Email)'
));
```

#### 5. Add multiple rules on multiple values
Mix and match everthing from above
```php
$validator->add(array(
    'email' => 'required | email',
    'name' => array(
         'required',
         array('minlength', 'min=2', '{label} must have at least {min} characters', 'Email'),
    )
));
```

## Validating data

```php
$validationResult = $validator->validate($_POST); // TRUE or FALSE
$messages = $validator->getMessages(); // array with all error messages
$emailErrorMessages = $validator->getMessages('email'); // error messages for the email address
```

If for whatever reason you need to manually set error messages you can do it like so
```php
$validator->addMessage('email', 'This value should be an email address');
```
and clear them
```php
$validator->clearMessages();
```

Anytime you execute `$validator->validate($values)` the validation messages are cleared (even those set manually).

