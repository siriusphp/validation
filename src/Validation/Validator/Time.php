<?php

namespace Sirius\Validation\Validator;

class Time extends Date {
	
    protected static $defaultMessageTemplate = 'This input must be a time having the format {format}';

    protected $options = array(
    	'format' => 'H:i:s'
    );

}