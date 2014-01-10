<?php

namespace Sirius\Validation\Validator\File;

use Sirius\Validation\Validator\AbstractValidator;

class Image extends AbstractValidator {

	cons OPTION_ALLOWED_IMAGES = 'allowed';

    protected static $defaultMessageTemplate = 'File is not a valid image (only {image_types} are allowed)';

    protected $options = array(
    	static::OPTION_ALLOWED_IMAGES = array('jpg', 'png', 'gif')
    );

    protected $imageTypesMap = array(
    	IMAGETYPE_GIF => 'gif',
    	IMAGETYPE_JPEG => 'jpg',
    	IMAGETYPE_JPEG2000 => 'jpg',
    	IMAGETYPE_PNG => 'png',
    	IMAGETYPE_PSD => 'psd',
    	IMAGETYPE_BMP => 'bmp',
    	IMAGETYPE_ICO => 'ico',
    );

    function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;
        if (!file_exists($value)) {
	        $this->success = false;
	    } else {
	    	$imageInfo = getimagesize($value);
	    	$extension = isset($this->imageTypesMap[$imageInfo[2]]) ? $this->imageTypesMap[$imageInfo[2]] : false;
	    	$this->success = ($extension && in_array($extension, $this->options[static::OPTION_ALLOWED_IMAGES]));
	    }
        return $this->success;
    }

    function getPotentialMessage() {
    	$message = parent::getPotentialMessage();
    	$imageTypes = array_map($this->options[static::OPTION_ALLOWED_IMAGES], '\strtouper');
    	$message->setVariables(array(
    		'image_types' => implode(', ', $imageTypes);
    	));
    	return $message;
    }
}