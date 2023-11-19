#Sirius Validation

[![Source Code](http://img.shields.io/badge/source-siriusphp/validation-blue.svg?style=flat-square)](https://github.com/siriusphp/validation)
[![Latest Version](https://img.shields.io/packagist/v/siriusphp/validation.svg?style=flat-square)](https://github.com/siriusphp/validation/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://github.com/siriusphp/validation/blob/master/LICENSE)
[![Build Status](https://img.shields.io/travis/siriusphp/validation/master.svg?style=flat-square)](https://travis-ci.org/siriusphp/validation)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/siriusphp/validation.svg?style=flat-square)](https://scrutinizer-ci.com/g/siriusphp/validation/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/siriusphp/validation.svg?style=flat-square)](https://scrutinizer-ci.com/g/siriusphp/validation)
[![Total Downloads](https://img.shields.io/packagist/dt/siriusphp/validation.svg?style=flat-square)](https://packagist.org/packages/siriusphp/validation)

Sirius Validation is a library for data validation. It offers:

1. [validator object](validator.md)
2. [45 build-in validation rules](validation_rules.md). There are validators for strings, array, numbers, emails, URLs, files and uploads
3. [validation helper](validation_helper.md) to simplify the validation of single values

Out-of-the-box, the library can handle `array`s, `ArrayObject`s and objects that have implemented the `toArray` method.
In order to validate other data containers you must create a [`DataWrapper`](https://github.com/siriusphp/validation/blob/master/src/Validation/DataWrapper/WrapperInterface.php) so that the validator be able to extract data from your object.

##Elevator pitch

```php
$validation = new \Sirius\Validation\Validator;

// let's validate an invoice form
$validator->add(array(
    // :Date specifies the label for the field. It will be used in the error messages
    'order_date:Date' => 'required | date',
    // `clientexists` is an app-specific rule
	'client_id:Client' => 'required | clientexists', 
	// apply the same rule for an array of items
	'notify_recipients[*]:Send invoice to' => 'email',
	// apply a rule to a specific item in the array 
	'shipping_address[line_1]:Address' => 'required'
	'shipping_address[city]:City' => 'required'
	'shipping_address[state]:State' => 'required'
	'shipping_address[country]:Country' => 'required',
	'lines[*]price:Price' => [
	    // the price is required only if a product was selected
	    'requiredWith(item=lines[*]product_id)',
	    // another app-specific rule applied to the price, specified as a class 
	    'MyApp\Validator\Rule\InvoiceItemPrice' 
	];,
	'lines[*]quantity:Quantity' => [
	    // the price is required only if a product was selected
	    'requiredWith(item=lines[*]product_id)',
	    // here we have a custom validation rule with no parameters AND a custom error message
	    'quantity_in_stock()(The quantity is not valid)'
	    ; 
	)
));
```

## Why this style? 

1. Because I want to be able to do the following in my views
```php
<div class="error">
<?php echo $messages['lines[0][price]']; ?>
</div>
```
This may seem counter-productive but remember that your forms' input fields may look like this
```html
<input name="lines[0][price]" value="abc">
```

2. Because, If I am to do server side validation I can receive a JSON
```json
{
	"errors": {
		"recipients[0]": "Field must be a valid email",
		"lines[2][price]": "Price must be a number",
	}
}
```
This way I can find the corresponding HTML inputs and show the error
```javascript
$.each(errors, function(message, field) {
	$('[name="' + field + '"]')
		.parents('div.control')
		.addClass('error')
		.append('<div class="error">'+message+'</div>');	
});
```

3. If I am to do client-side validation I want to do the following
```html
<script>
<?php
$clientSideRules = $helperThatCompilesTheClientSideValidation->getValidationRules($validator);
?>
$('#myForm').validate(<?php echo json_encode($clientSideRules))?>);
</script>
```
