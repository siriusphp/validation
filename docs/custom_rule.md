---
title: Custom validation rules
---

# Custom validation rule

The code below explains how you should properly create your custom validation rule

```php
namespace MyApp\Validation\Rule;
use Latinosoft\Validation\Rule\AbstractRule;

class ThisOrThat extends AbstractRule {
    
    // first define the error messages
    const MESSAGE = 'This field should be {this} or {that}';
    const LABELED_MESSAGE = '{label} should be {this} or {that}';
    
    // create constants for the options names help your IDE help you
    const OPTION_THIS = 'this';
    const OPTION_THAT = 'that';
    
    // specify default options if you want
    protected $options = array(
        'this' => 'a',
        'that' => 'b'
    );
    
    // if you want to let the user pass the options as a CSV (eg: 'this,that')
    // you need to provide a `optionsIndexMap` property which will convert the options list
    // into an associative array of options
    protected $optionsIndexMap = array(
        0 => self::OPTION_THIS,
        1 => self::OPTION_THAT
    );

    function validate($value, $valueIdentifier = null) {
        return $value == $this->options[self::OPTION_THIS] || $value == $this->options[self::OPTION_THAT];
    }

}
```

The `$valueIdentifier` is the path of the value being validated within the [validation context](validation_context.md).

### Use the validator in your app

```php
use Latinosoft\Validation\Validator;
use MyApp\Validation\Rule\ThisOrThat;

$validator = new Validator();
$validator->add('key', 'MyApp\Validation\Rule\ThisOrThat', array(
    ThisOrThat::OPTION_THIS => 'c',
    ThisOrThat::OPTION_THAT => 'd'
));

// or less verbose
$validator->add('key', 'MyApp\Validation\Rule\ThisOrThat(c,d)');
```