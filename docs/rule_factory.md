# RuleFactory

The [validator object](validator.md) uses a `RuleFactory` to construct the validation rules based on the parameters received by the `add()` method.

The `RuleFactory` is a simple registry-like object which maps validation names to [validation rules classes](rules.md).

If your app uses dependency injection you can create one validator factory to be used by all the validators

## Registering validator classes

The code below assumes you got a handle of the $validationFactory from a registry or dependency injection container.

```
use Sirius\Validation\RuleFactory;
use Sirius\Validation\Validator;

$ruleFactory = new RuleFactory;
// register new validation class
$ruleFactory->register('user_exists', 'MyApp\UserExistsValidator');

// overwrite an already registered class
$ruleFactory->register('email', 'MyApp\VeryPowerfullEmailValidator');

$validator = new Validator($ruleFactory);
```