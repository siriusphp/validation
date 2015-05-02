---
title: Syntactic sugar
---

# Syntactic sugar

##### 1. Add multiple rules at once by using just a string
```php
// separate rules using ' | ' (space, pipe, space)
$validator->add('email', 'required | email');
```

##### 2. Add rule with parameters and custom messages using only a string
```php
// parameters set as JSON string
$validator->add('name', 'minlength({"min":2})({label} must have at least {min} characters)(Name)');
// or parameters set as query string
$validator->add('name', 'minlength(min=2)({label} must have at least {min} characters)(Name)');

// the above examples are similar to
$validator->add('name', 'minlength', array('min' => 2), '{label} must have at least {min} characters', 'Name');
```

##### 3. Mix and match 1 and 2
```php
$validator->add('name', 'required | minlength({"min":2})({label} must have at least {min} characters)(Name)');
```

Of course this means the error message cannot contain the ` | ` sequence

##### 4. Add multiple rules per value
```php
$validator->add(
    // add the label after the selector so you don't have to pass the label to every rule
    'email:Email', 
    array(
        // only through the name of the validation rule
        'email',
        // or with all parameters
        array('minlength', 'min=2', '{label} must have at least {min} characters'),
    )
);
```

##### 5. Add multiple rules on multiple values
Mix and match everthing from above
```php
$validator->add(array(
    'email:Email' => 'required | email',
    'name:Name' => array(
         'required',
         array('minlength', 'min=2', '{label} must have at least {min} characters'),
    )
));
```

