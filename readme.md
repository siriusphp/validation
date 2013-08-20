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

[go to the documentation](docs/index.md)
