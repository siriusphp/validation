---
title: Complex validation rules
---

# Complex validation rules

Usually a rule validation class doesn't need too much to do its job but there are situations when your validator needs access to external resources (eg: a database connection).
In this case, they cannot be registered as classes on the [rule factory](rule_factory.md). Still, you have a few choices: 

1. use the validator as a callback
2. pass the dependencies as options
3. extend the `RuleFactory` class

###1. Using the validator as a callback 

```
namespace MyApp\Validation\Rule;
use Latinosoft\Validation\Validator\AbstractRule;

class UniqueUsername extends AbstractRule{
	
	protected $db;

	function __construct($options = array(), $dbConn) {
	    parent::__construct($options);
		$this->db = $dbConn;
	}

	function validate($value) {
		// here you check if the username is taken or not
	}
}
```

Because the factory instantiates the rules only using the options as a single parameter, you cannot do 

```
$ruleFactory->register('username', 'MyApp\Validation\Rule\UniqueUsername');
```

So you have to do attach the validator as a callback to your validator object

```
use MyApp\Validation\Rule\UniqueUsername;

// create an instance of the validator
$UniqueUsername = new UniqueUsername($dbConn);
// or get it from the container
$UniqueUsername = $dependencyInjectionContainer->get('UniqueUsername');

$validator = new Validator($ruleFactory);
// the second parameter for the add() can be the name of a rule or a callback
$validator->add('username', array($UniqueUsername, 'validate'));
```

###2. Pass the dependencies as options

```
namespace MyApp\Validation\Rule;
use Latinosoft\Validation\Validator\AbstractRule;

class UniqueUsername extends AbstractRule{
	
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
$dbConn = $serviceLocator->get('dbconnection');
$validator->add('username', 'MyApp\Validation\Rule\UniqueUsername', array(
	'db_connection' => $dbConn
));
```

###3. Extend the `RuleFactory` class

This solution depends on your application structure but assuming you have a dependency injection container you can use a different `RuleFactory` object that is capable of creating rules using a service locator or even the dependency injection container.

The example below does not use a real-life implementation, it's just food for thought

```php
namespace MyApp\Validation;

class RuleFactory extends \Latinosoft\Validation\RuleFactory {
	
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

Configure the service in the container

```php

$container->set('RuleFactory', function($container) {
    $factory = new MyApp\Validation\RuleFactory();
    $factory->setDic($container);
    return $factory;
});
```

Configure the validation rule in the container

```php

$container->set('MyApp\Validation\Rule\UniqueUsername', function($options) use ($container) {
    $rule = new MyApp\Validation\Rule\UniqueUsername($options);
    $rule->setDbConn($container->get('db_connection'));
    return $rule;
});
```


You must make sure that your validation uses the proper `RuleFactory`

```php
use Latinosoft\Validation\Validator;

$validator = new Validator($container->get('RuleFactory'));
$validator->add('username', 'MyApp\Validation\Rule\UniqueUsername');
```