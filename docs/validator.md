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

Custom messages
=====
There's a list of default validation messages associated with the class. You can use the `setGlobalDefaultMessages()` static method to set up globally available messages

```php
Validator::setGlobalDefaultMessages(array(
    'required' => 'This field is required',
    'email' => 'This field is not a valid email address',
    'custom_validation_method' => 'This field should meet our validation criteria'
));

// or you can set them individually
Validator::setGlobalDefaultMessages('required', 'You must provide something here');
```

In your validator instance you can set up validation messages with each rule. The error messages are passed through the `compileMessage` function which allows you some flexibility

1. Reusable messages using an array
```php
$validator->add('username', 'required', null, array('%s is required', 'Username'));
```
The code above will compile the message into 'Username is required'.

2. Customizable messages based on rule parameters
```php
$validator->add('username', 'minLength', array(5), array('%s should have at least {0} characters', 'Username'));
```
The code above will generate an error message that is 'Username should have at least 2 characters'. The sequences `{x}` are replaced with the values from the parameters array.

3. Translatable messages
The library doesn't offer support for translation but you can implement your own custom `compileMessage` method to allow for translation. Assuming your translation callback is `__translate` you can have something like
```php
protected function compileMessage($message, $params) {
    if (is_array($message)) {
        // this will translate all items in $message
        array_walk($message, '__translate');
        $message = call_user_func_array('sprintf', $message);
    }
    // this will replace the {x} sequences with their corresponding values from the parameter
    if (is_array($params) and count($params) > 0) {
        foreach ($params as $key => $value) {
            if (strpos($message, "{{$key}}") !== false) {
                $message = str_replace("{{$key}}", (string)$value, $message);
            }
        }
    }
    return $message;
}
```