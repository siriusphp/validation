<?php

namespace Sirius\Validation\Validator\File;

use Sirius\Validation\Validator\AbstractValidator;

class Extension extends AbstractValidator {

	cons OPTION_ALLOWED_EXTENSIONS = 'allowed';

    protected static $defaultMessageTemplate = 'File does not have an acceptable extension ({file_extensions})';

    protected $options = array(
    	static::OPTION_ALLOWED_EXTENSIONS = array()
    );

    function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;
        if (!file_exists($value)) {
	        $this->success = false;
	    } else {
	    	$extension = strtolower(substr($value, strrpos($value, '.'), 10));
	    	$this->success = in_array($extension, $this->options[static::OPTION_ALLOWED_EXTENSIONS]);
	    }
        return $this->success;
    }

    function getPotentialMessage() {
        $message = parent::getPotentialMessage();
        $fileExtensions = array_map($this->options[static::OPTION_ALLOWED_EXTENSIONS], '\strtouper');
        $message->setVariables(array(
            'file_extensions' => implode(', ', $fileExtensions);
        ));
        return $message;
    }
}