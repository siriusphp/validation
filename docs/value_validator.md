# Using the ValueValidator object

You can use the [validation helper](validation_helper.md) to validate single values but that will only return true/false. 

If you need to validate a single value and also retrieve the error message you can use `ValueValidator` class

```php
namespace Latinosoft\Validation;

// Set up the validator.
// If the constructor parameters are not provided they will be created from defaults
$ruleFactory = new RuleFactory;
$errorMessagePrototype = new ErrorMessage;
$titleValidator = new ValueValidator($ruleFactory, $errorMessagePrototype);

// add the rules
$titleValidator->add('required', null, 'You must provide the post title');
$titleValidator->add('minlength', 'min=20', null, 'Title'); // use the default message, set a custom label

$titleValidator->validate('Too short'); // returns false
$titleValidator->getMessages();
```

