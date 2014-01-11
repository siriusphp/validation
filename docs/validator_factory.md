# ValidatorFactory

The [validator object](validator.md) uses a `ValidatorFactory` to construct the validation rules based on the parameters received by the `add()` method.

The `ValidatorFactory` is a simple registry which maps validation names to [validator classes](validators.md).

If your app uses dependency injection you can create one validator factory to be used by all the validators

## Registering validator classes

The code below assumes you got a handle of the $validationFactory from a registry or dependency injection container.

```
// register new validation class
validationFactory->register('user_exists, 'MyApp\UserExistsValidator');

// overwrite an already registered class
$validationFactory->register('email', 'MyApp\VeryPowerfullEmailValidator');
```

## Register validator classes from the validator instance

```
$validator->getValidatorFactory()->register('user_exists, 'MyApp\UserExistsValidator');
$validator->add('user_id', 'user_exists');
```