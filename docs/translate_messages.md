---
title: Translating the error messages
---

# Translating the error messages

There are a couple of ways to translate the error messages, each having it's pros and cons:

1. Postpone the translation until display
2. Use a translatable error message class
3. Use translated string for the message templates

### 1.Postpone the translation until display

```php
// make a function especially for that
echo translateErrorMessageObjects($validator->getMessages('title'));
```

### 2. Use a translatable error message class

```php
class TranslatableErrorMessage extends Latinosoft\Validation\ErrorMessage {
    protected $translator;
    
    function __construct($translator, $template, $options = array()) {
        parent::__construct($template, $options);
        $this->translator = $translator;
    }
    
	function __toString() {
		// write your implementation here
	}
}

// later when constructing your validators
$validator = new Latinosoft\Validation\Validator(null, new TranslatableErrorMessage);
```

### 3.Use translated string for the message templates

```php
// in the validator
$validator->add('title:' . __('Title'), 'maxlength', 'max=100', __('{label} must have less than {max} characters'));

// or the rule factory
$ruleFactoryInstance->setErrorMessages('maxlength', __('This field must have less than {max} characters'), __('{label} must have less than {max} characters'));
```
