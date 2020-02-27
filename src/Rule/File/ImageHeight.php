<?php
declare(strict_types=1);
namespace Sirius\Validation\Rule\File;

use Sirius\Validation\Rule\AbstractRule;

class ImageHeight extends AbstractRule
{
    const OPTION_MAX = 'max';
    const OPTION_MIN = 'min';

    const MESSAGE = 'The file should be at least {min} pixels tall';

    const LABELED_MESSAGE = '{label} should be at least {min} pixels tall';

    protected $options = [
        self::OPTION_MAX => 1000000,
        self::OPTION_MIN => 0,
    ];

    public function validate($value, string $valueIdentifier = null):bool
    {
        $this->value = $value;
        if (!file_exists($value)) {
            $this->success = false;
        } else {
            $imageInfo     = getimagesize($value);
            $height        = isset($imageInfo[1]) ? $imageInfo[1] : 0;
            $this->success = $height &&
                             $height <= $this->options[self::OPTION_MAX] &&
                             $height >= $this->options[self::OPTION_MIN];
        }

        return $this->success;
    }
}
