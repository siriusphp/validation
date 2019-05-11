---
title: Simple validation example
---

# Simple example

### Initialize your validator class

Let's consider a simple contact form that has the following field: `name`, `email`, `phone` and `message`.

```php
use Latinosoft\Validation\Validator;

$validator = new Validator();
$validator->add(
    array(
    
        // the key is in the form [field]:[label]
        'name:Name' => 'required',
        
        // you can have multiple rules for the same field
        'email:Your email' => 'required | email',
        
        // validators can have options
        'message:Your message' => 'required | minlength(10)',
        
        // and you can overwrite the default error message
        'phone:Phone' => 'regex(/your_regex_here/)(This field must be a valid US phone number)'
    )
);
```

### Perform validation on some data

```php

if ($validator->validate($_POST)) {

    // send notifications to stakeholders
    // save the form data to a database

} else {

    // send the error messages to the view
    $view->set('errors', $validator->getMessages();

}
```

Easy-peasy, right?
