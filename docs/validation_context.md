---
title: The validation context
---

# The validation context

Most validation libraries allow you validate a set of data (usually an array) and sometimes a piece of data is validated depending on the context.

Let's say the user must fill a form an order form where it must supply the shipping and billing address. 
The form has a checkbox "Use a different billing address" which, when checked, shows the billing address details (stree, city etc).
Those fields become required only when the user checked that option.

For this reason, the entire validation rules within the Latinosoft\Validation library are "context-aware". This means the data-set to be validated is passed to each rule.

This can be seen in action in the [RequiredWith](https://github.com/siriusphp/validation/blob/master/src/Rule/RequiredWith.php#L13) rule.

The validator receives a set of data to be validated which can be anything (the `_POST` array, an application model etc). 
By default the library can handle arrays, ArrayObjects or objects that have the `toArray` method. 

If you use any of these structures the data you pass is converted to an array and wrapped inside an [`ArrayWrapper`](https://github.com/siriusphp/validation/blob/master/src/DataWrapper/ArrayWrapper.php).
This allows the validation rules to access other information form the context to retrieve it using something like `$this->context->getItemValue($aPathToValue)`

## Validating "non-standard" structures

In order to validate structures that do not meet the criteria above, you have to create your own `DataWrapper` which would allow the validation rules to extract data from that structure.

Or you can simply implement a `toArray` method to your objects :)