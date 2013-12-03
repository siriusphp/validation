<?php
namespace Sirius\Validation\Validator;

class Callback extends AbstractValidator
{

    const OPTION_CALLBACK = 'callback';

    const OPTION_ARGUMENTS = 'arguments';

    protected static $defaultMessageTemplate = 'This input does not meet the validation criteria';

    function getUniqueId()
    {
        $uniqueId = get_called_class();
        // the callback is a function name (eg: is_int) or a static class method (eg: MyClass::method)
        if (is_string($this->options['callback'])) {
            $uniqueId .= $this->options['callback'];
        } else 
            if (is_array($this->options['callback'])) {
                // the callback is an array that points to a static class method (eg: array('MyClass', 'method'))
                if (is_string($this->options['callback'][0])) {
                    $uniqueId .= '|' . implode('::', $this->options['callback']);
                } else 
                    if (is_object($this->options['callback'][0])) {
                        $uniqueId .= '|' . spl_object_hash($this->options['callback'][0]) . '->' . $this->options['callback'][1];
                    }
            } else 
                if (is_object($this->options['callback']) && $this->options['callback'] instanceof \Closure) {
                    $uniqueId .= '|' . spl_object_hash($this->options['callback']);
                }
                
        if (isset($this->options['arguments'])) {
            $uniqueId .= '|' . json_encode(ksort($this->options['arguments']));
        }
        return $uniqueId;
    }

    function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;
        if (! isset($this->options['callback']) || ! is_callable($this->options['callback'])) {
            $this->success = true;
        } else {
            $args = (isset($this->options['arguments'])) ? (array) $this->options['arguments'] : array();
            array_unshift($args, $value);
            array_push($args, $valueIdentifier, $this->context);
            $this->success = (bool) call_user_func_array($this->options['callback'], $args);
        }
        return $this->success;
    }
}