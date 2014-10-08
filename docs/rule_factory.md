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

## Q&A

### My validators are more complex (ie: they have dependencies), how can I use them?

If your validator need access to external resources (eg: a database connection) they cannot be registered as classes on the validator factory you have 2 choices: **use the validator as a callback** or **pass the dependencies as options**

#### Using the validator as a callback 

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

#### Pass the dependencies as options

```
namespace MyApp\Validation;
use Sirius\Validation\Validator\AbstractValidator;

class UsernameValidator extends AbstractValidator{
	
	function __construct($options) {
		// $options contain the dependencies
	}

	function validate($value) {
		$options['db_connection']->query('SELECT * FROM users WHERE username=?', $value);
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