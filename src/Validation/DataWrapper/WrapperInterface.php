<?php

namespace Sirius\Validation\DataWrapper;

interface WrapperInterface {
    
    public function setData($data);
    
    public function getData();
    
    public function getItemValue($item);
    
    public function setItemValue($item, $value = null);
}