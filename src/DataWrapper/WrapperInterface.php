<?php

namespace Sirius\Validation\DataWrapper;

interface WrapperInterface {
    
    public function setData($data);
    
    public function getItemValue($item);
    
    public function getItemsBySelector($selector);
    
}