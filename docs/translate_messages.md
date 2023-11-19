---
title: Translating the error messages
---

# Translating the error messages

There are a couple of ways to translate the error messages, each having its pros and cons:

1. Postpone the translation until display
2. Use a translatable error message class

### 1.Postpone the translation until display

```php
// make a function especially for that
echo translateErrorMessageObjects($validator->getMessages('title'));
```

### 2. Use a translatable error message class

```php
class TranslatableErrorMessage extends Sirius\Validation\ErrorMessage {
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
$translator = new MyTranslator();
$validator = new Sirius\Validation\Validator(null, new TranslatableErrorMessage($translator));
```
