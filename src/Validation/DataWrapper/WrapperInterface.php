<?php

namespace Sirius\Validation\DataWrapper;

interface WrapperInterface {
    
    public function setData($data);
    
    public function getData();
    
    public function getItemValue($item);
    
    public function getItemsBySelector($selector);
    
    public function setItemValue($item, $value = null);
}