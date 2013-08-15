Sirius Validation
===============

Sirius Validation is a library for validating arrays.

Why (just) arrays?
============
1. Arrays are the most common data containers. 
2. Forms are populated via arrays and send data to the server via arrays
3. Arrays can be converted from/into JSONs making it easy to move data from server-side to client-side
4. Domain models can be converted into arrays and validate the array copy

If a library tries to do too much it will most likely be very hard to use and you easily implement ways to exchange data from and into arrays.
If you want more "muscle" ZF2's validation chains are a better choice, although you may pull your hair using them.

Goal
============
I started this library having in mind a form representing an invoice, which seems one of the most difficult forms to validate
- it has a date which can be localized
- it has a billing address section that contains various fields, each with its own validation rules
- it has a shipping address section whos fields must be provided and validated only if the "same as billing address" checkbox is unchecked
- it has at least on line (of product/service) with name, quantity and price
- it has a payment method
- it has payment details which must be validated against the rules required by the payment method
- it can have a list of recipients that will get the invoice by email if the user chooses to fill them out.

The same example may be applied to an API that receives data which must be validated against these rules

The code
============
For the example above the code should look like this:

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
// if you need to apply a condition you can do it like this
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

1. Because I want to be able to do the following in my views
```html
<div class="error">
<?php echo $messages['lines[0][price]']; ?>
</div>
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

3. If I am to do client-side validation I want to do the following
```html
<script>
$('#myForm').validate(<?php echo json_encode($myHelperOfChoice->getValidationRules($validator)))?>);
</script>
```

Frequently Asked Questions?
=====================
1. Translation of the error messages?
I think the error messages should be translated at the moment you build the validation. In the case of forms you need to be able to retrieve the messages translated for client-side validation so the translation will be executed anyway before the validation.
If you really need to postpone the translation of the messages you can extend the class and alter the <code>compileMessage()</code> method

2. Shouldn't you sanitize the data before validating it?
Yes, you should. But it's not the scope of this library. I have another library for this purpose.