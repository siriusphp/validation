<?php

namespace Sirius\Validation;

class ErrorMessage {
    protected $template;
    protected $variables = array();
    
    function __construct($template, $variables = array()) {
        $this->setTemplate($template)
            ->setVariables($variables);
    }
    
    function setTemplate($template) {
        $this->template = (string) $template;
        return $this;
    }
    
    function setVariables($variables = array()) {
        foreach ($variables as $k => $v) {
            $this->variables[$k] = $v;
        }
        return $this;
    }
    
    function __toString() {
         
    }
}