# Using the ValueValidator object

You can use the [validation helper](helper.md) to validate single values but that will only return true/false. 

If you need to validate a single value and also retrieve the error message you can use `ValueValidator` class

```php
namespace Sirius\Validation;

$valueValidator = new ValueValidator(new RuleFactory, new ErrorMessage);
$valueValidator->add('required', null, 'You must provide the post title');
$valueValidator->add('minlength', 'min=20', 'The post title must have at least {min} characters');

$valueValidator->validate('Too short'); // return false
$valueValidator->getMessages();
```

