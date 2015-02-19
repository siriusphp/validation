<?php
namespace Sirius\Validation\Rule\File;

use Sirius\Validation\Rule\AbstractValidator;

class ImageWidth extends AbstractValidator
{
    const OPTION_MAX = 'max';
    const OPTION_MIN = 'min';

    protected static $defaultMessageTemplate = 'Image should be at least {min} pixels wide';

    protected $options = array(
        self::OPTION_MAX => 1000000,
        self::OPTION_MIN => 0,
    );

    public function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;
        if (!file_exists($value)) {
            $this->success = false;
        } else {
            $imageInfo = getimagesize($value);
            $width = isset($imageInfo[0]) ? $imageInfo[0] : 0;
            $this->success = $width && $width <= $this->options[self::OPTION_MAX] && $width >= $this->options[self::OPTION_MIN];
        }
        return $this->success;
    }
}
