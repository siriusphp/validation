# Using the ValueValidator object

You can use the [validation helper](helper.md) to validate single values but that will only return true/false. 

If you need to validate a single value and also retrieve the error message you can use `ValueValidator` class

```php
namespace Sirius\Validation;

// Set up the validator.
// If the constructor parameters are not provided they will be created from defaults
$ruleFactory = new RuleFactory;
$errorMessagePrototype = new ErrorMessage;
$valueValidator = new ValueValidator($ruleFactory, $errorMessagePrototype);

// add the rules
$valueValidator->add('required', null, 'You must provide the post title');
$valueValidator->add('minlength', 'min=20', null, 'Label for the field');

$valueValidator->validate('Too short'); // returns false
$valueValidator->getMessages();
```

