<?php

namespace Sirius\Validation\DataWrapper;

use Sirius\Validation\Utils;

class ArrayWrapper implements WrapperInterface {
    protected $data = array();

    function __construct($data = array()) {
        $this->setData($data);    
    }
    
    function setData($data) {
        if (is_object($data)) {
            if ($data instanceof \ArrayObject) {
                $data = $data->getArrayCopy();
            } elseif (method_exists($data, 'toArray')) {
                $data = $data->toArray();
            }
        }
        if (! is_array($data)) {
            throw new \InvalidArgumentException('Data passed to validator is not an array');
        }
        $this->data = $data;
        return;
    }
    
    function getData() {
        return $this->data;
    }
    
    function getItemValue($item) {
        return Utils::arrayGetByPath($this->data, $item);
    }
    
    function setItemValue($item, $value = null) {
        $this->data = Utils::arraySetBySelector($this->data, $item, $value, true);
        return $this;
    }
}