<?php

namespace Sirius\Validation\Rule\File;

use Sirius\Validation\Rule\AbstractRule;

class Image extends AbstractRule
{
    const OPTION_ALLOWED_IMAGES = 'allowed';

    const MESSAGE = 'The file is not a valid image (only {image_types} are allowed)';

    const LABELED_MESSAGE = '{label} is not a valid image (only {image_types} are allowed)';

    protected $options = array(
        self::OPTION_ALLOWED_IMAGES => array( 'jpg', 'png', 'gif' )
    );

    protected $imageTypesMap = array(
        IMAGETYPE_GIF      => 'gif',
        IMAGETYPE_JPEG     => 'jpg',
        IMAGETYPE_JPEG2000 => 'jpg',
        IMAGETYPE_PNG      => 'png',
        IMAGETYPE_PSD      => 'psd',
        IMAGETYPE_BMP      => 'bmp',
        IMAGETYPE_ICO      => 'ico',
    );

    public function setOption($name, $value)
    {
        if ($name == self::OPTION_ALLOWED_IMAGES) {
            if (is_string($value)) {
                $value = explode(',', $value);
            }
            $value = array_map('trim', $value);
            $value = array_map('strtolower', $value);
        }

        return parent::setOption($name, $value);
    }

    public function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;
        if (! file_exists($value)) {
            $this->success = false;
        } else {
            $imageInfo     = getimagesize($value);
            $extension     = isset($this->imageTypesMap[$imageInfo[2]]) ? $this->imageTypesMap[$imageInfo[2]] : false;
            $this->success = ($extension && in_array($extension, $this->options[self::OPTION_ALLOWED_IMAGES]));
        }

        return $this->success;
    }

    public function getPotentialMessage()
    {
        $message    = parent::getPotentialMessage();
        $imageTypes = array_map('strtoupper', $this->options[self::OPTION_ALLOWED_IMAGES]);
        $message->setVariables(
            array(
                'image_types' => implode(', ', $imageTypes)
            )
        );

        return $message;
    }
}
