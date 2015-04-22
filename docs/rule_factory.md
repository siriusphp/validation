# RuleFactory

Every [validator object](validator.md) uses a `RuleFactory` to construct the validation rules based on the parameters received by the `add()` method.

The `RuleFactory` is a simple registry-like object which stores

1. the names of validation rules and their corresponding [validation rules classes](rules.md).
2. the default error messages for each validation rule. These are used when there is NO label attached to the field
3. the default **LABELED** error messages for each validation rule. These are used when the field has a label attached

By configuring the default error messages in the `RuleFactory` you don't have to repeat yourself. Since you should use a single `RuleFactory` for the entire app, there will be a single place where you can set the error message for, let's say, the `required` rule.

## Registering validator classes

The code below assumes you got a handle of the $validationFactory from a registry or dependency injection container.

```
use Sirius\Validation\RuleFactory;
use Sirius\Validation\Validator;

$ruleFactory = new RuleFactory;
// register new validation class
$ruleFactory->register('user_exists', 'MyApp\UserExistsValidator', $defaultMessageWithoutLabel, $defaultMessageWithLabel);

// overwrite an already registered class
$ruleFactory->register('email', 'MyApp\VeryPowerfullEmailValidator', $defaultMessageWithoutLabel, $defaultMessageWithLabel);

// override just the error messages
$ruleFactory->setMessages('email', $defaultMessageWithoutLabel, $defaultMessageWithLabel);
// obviously you can override only what you need
$ruleFactory->setMessages('email', null, $defaultMessageWithLabel);

$validator = new Validator($ruleFactory);
```

## Complex validators

If your validator need access to external resources (eg: a database connection) they cannot be registered as classes on the validator factory you a few choices: 

1. use the validator as a callback
2. pass the dependencies as options
3. extend the `RuleFactory` class

###1. Using the validator as a callback 

```
namespace MyApp\Validation;
use Sirius\Validation\Validator\AbstractValidator;

class UsernameValidator extends AbstractValidator{
	
	protected $db;

	function __construct($dbConn) {
		$this->db = $dbConn;
	}

	function validate($value) {
		// here you check if the username is taken or not
	}
}
```

Because the factory instanciates the validators only using the options as a single parameter, you cannot do 

```
$ruleFactory->register('username', 'MyApp\Valdiation\UsernameValidator');
```

So you have to do attach the validator as a callback to your validator object

```
// create an instance of the validator
$usernameValidator = new UsernameValidator($dbConn);
// or get it from the container
$usernameValidator = $dependencyInjectionContainer->get('UsernameValidator');

$validator = new Validator($ruleFactory);
$validator->add('username', array($usernameValidator, 'validate'));
```

###2. Pass the dependencies as options

```
namespace MyApp\Validation;
use Sirius\Validation\Validator\AbstractValidator;

class UsernameValidator extends AbstractValidator{
	
	function __construct($options) {
		// $options contain the dependencies
	}

	function validate($value) {
		$this->options['db_connection']->query('SELECT * FROM users WHERE username=?', $value);
		// continue here
	}
}
```

and in your validator you do something like

```
$validator->add('username', 'MyApp\Validation\UsernameValidator', array(
	'db_connection' => $dbConn
));
```

###3. Extend the `RuleFactory` class

This solution depends on your application structure but assuming you have a dependency injection container you can use a different `RuleFactory` object that is capable of creating rules using a service locator or even the dependency injection container.

The example below is just food for thought

```php
namespace MyApp\Validation;

class RuleFactory extends \Sirius\Validation\RuleFactory {
	
	protected $dic;

	function setDic(DependecyInjectionContainerInterface $dic) {
		$this->dic = $dic;
	}

	protected function constructValidatorByNameAndOptions($name, $options) {
		$validatorClass = $this->validatorsMap[$name];
		$validator = $this->dic->createInstanceWithParams($validatorClass, array($options));
		return $validator;
	}
}
```