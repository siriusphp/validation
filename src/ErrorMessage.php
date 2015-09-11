<?php

namespace Sirius\Validation;

class ErrorMessage
{
    protected $template = 'Invalid';
    protected $variables = array();

    public function __construct($template = '', $variables = array())
    {
        $this->setTemplate($template)
             ->setVariables($variables);
    }

    public function setTemplate($template)
    {
        $template = trim((string) $template);
        if ($template) {
            $this->template = (string) $template;
        }

        return $this;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function setVariables($variables = array())
    {
        foreach ($variables as $k => $v) {
            $this->variables[$k] = $v;
        }

        return $this;
    }

    public function getVariables()
    {
        return $this->variables;
    }

    public function __toString()
    {
        $result = $this->template;
        foreach ($this->variables as $k => $v) {
            if (strpos($result, "{{$k}}") !== false) {
                $result = str_replace("{{$k}}", $v, $result);
            }
        }

        return $result;
    }
}
