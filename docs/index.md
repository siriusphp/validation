Sirius\Validation Documentation
=======

Include it in your project
======
```php
require_once('path/to/sirius/validation/folder/autoloader.php');
```

Classes
======

1. [Validation\Helper](helper.md) - helper class also useful for simple validation tasks
2. [Validation\Validator](helper.md) - main class used for validation


Example
======

Give the scenario presented in the readme file the code should look like this:

```php
$validator = new \Sirius\Validation\Validator;
$validator->add('date', 'required', null, 'Field is required');
$validator->add('date', 'date', 'YYYY-mm-dd', 'Field must be a valid date formated as YYYY-mm-dd (eg: 2013-07-12)');
$validator->add('billing_address[line_1]', 'required', null, 'Must provide the billing address');
$validator->add('billing_address[country]', 'required', null, 'Must provide the country of the billing address');
$validator->add('billing_address[country]', 'countryCode', null, 'Country is not valid');
// continue for the billing address

// add a named condition that will be used to determine if validation should take place 
$validator->condition('shipping_is_different', function($data) {
	return !$data['shipping_same_as_billing'];
});
$validator->add('shipping_address[line_1]', 'required', null, 'Must provide the shipping address', 'shipping_is_different');
$validator->add('shipping_address[country]', 'required', null, 'Must select the country for the shipping address', 'shipping_is_different');
// continu for the shipping address

$validator->add('lines', 'minSize', 1, 'The invoice must have at least one line.');
// no need to provide the validation message, there are defaults for that
$validator->add('lines[*][name]', 'required');
// alternative way to write more validation rules in one line
$validator->add('lines[*][quantity]', 'required[]Quantity not provided | number[]Quantity must be a number | greaterThan[0]Quantity must be greater than zero');
// if you need to apply a condition you can do it <li></li>ike this
$validator->add('lines[*][quantity]', 'if(condition)greaterThan[0]')
// add multiple validation rules
$validator->add('lines[*][price]', array(
	array('required', null, 'Price not provided',
	array('number', null, 'Price must be a number'),
));


$validator->add('recipients[*]', 'email', false, 'This field must be a valid email');

// this is how you execute the validation
$validator->validate($data);

// retrieving the error messages
$validator->getMessages();
$validator->getMessage('lines[0][price]');
$validator->getMessage('recipients[2]');
```


Why am doing it this way? 
=====

1. Because I want to be able to do the following in my views
```html
<div class="error">
<?php echo $messages['lines[0][price]']; ?>
</div>
```
This may seem counter-productive but remember that you input forms look like this
```html
<input name="lines[0][price]" value="abc">
```

2. Because, If I am to do server side validation I can receive a JSON
```javascript
{
	"errors": {
		"recipients[0]": "Field must be a valid email",
		"lines[2][price]": "Price must be a number",
	}
}
```
This way I can find the corresponding HTML inputs and show the error

3. If I am to do client-side validation I want to do the following
```html
<script>
$('#myForm').validate(<?php echo json_encode($myHelperOfChoice->getValidationRules($validator)))?>);
</script>
```
Since some elements of the form may be generated client-side (eg: using an "add line" button), the validation rules should be configured so they apply to future elements. If you have the validation rules applied to each input (ala Zend Framework 2) you'll have to either do more work on the client side or submit the form so it's validated on the server.

Frequently Asked Questions?
=====
1. Translation of the error messages?
I think the error messages should be translated at the moment you build the validation. In the case of forms you need to be able to retrieve the messages translated for client-side validation so the translation will be executed anyway before the validation.
If you really need to postpone the translation of the messages you can extend the class and alter the <code>compileMessage()</code> method

2. Shouldn't you sanitize the data before validating it?
Yes, you should. But it's not the scope of this library. I have another library for this purpose.