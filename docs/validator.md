Validation\Validator
=======

This is the class that will be instanciated to perform validation

```php
use Sirius\Validation\Validator;
$validator = new Validator($arrayContainingTheRules);
```

Adding validation rules
=====
```php
// syntax
$validator->add($selector, $rule, $params = null, $message = null, $condition = null);

// examples
$validator->add('username', 'required');
$validator->add('email', 'email', array(), 'Email address is not valid');
$validator->add('password', 'minLength', array(6), 'Passwork must have at least 6 characters');
```

The <code>$rule</code> must be a valid validation method registered within the Validation\Helper.
If it's not registered as a validation method you can use the <code>callback</code> method

```php
$validator->add('username', 'callback', array('MyClass::validateUsername'), 'Username is already taken');
```

The <code>$params</code> variable represents the variables for the validation method and they must be an array.

The <code>$message</code> is the message that will be associated with an item when the validation fails. 
If you don't provide it, there are some global defaults that you can use

The <code>$condition</code> is the name of the condition that must be met before proceeding with the validation

Validation conditions
=====
Sometimes you need to perform the validation only if a certain condition is met. 
For example if the user selected "other" from a list of unsubscription reasons you may want to force him to fill out the "details" field.

```php
$validator->addCondition('other_reason_is_checked', function($formData){
	return $formData['reasons']['other'] === 'checked';
});
$validator->add('reason_details', 'required', array(), 'Please tell use more about why you want to unsubscribe', 'other_reason_is_checked');
```
