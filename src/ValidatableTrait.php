<?php

namespace Sirius\Validation;

use Sirius\Validation\Validator;

trait ValidatableTrait implements ValidatableInterface{
    protected $validator;

    function setValidator($validator) {
        if (!$validator instanceof Validator) {
            throw new \InvalidArgumentException('The $validator argument is not a proper Validator object');
        }
        $this->validator = $validator;
        return $this;
    }

    function getValidator($validator) {
        if (!$this->validator) {
            $this->validator = new Validator();
        }
        return $this->validator;
    }

    function isValid() {
        if (!method_exists($this, 'toArray')) {
            throw new \BadMethodCallException('Object must have the "toArray" method to be able call "isValid"');
        }
        return $this->getValidator()->validate($this->toArray());
    }
}
