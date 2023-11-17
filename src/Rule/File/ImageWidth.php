<?php
declare(strict_types=1);

namespace Sirius\Validation\Rule\File;

use Sirius\Validation\Rule\AbstractRule;

class ImageWidth extends AbstractRule
{
    const OPTION_MAX = 'max';
    const OPTION_MIN = 'min';

    const MESSAGE = 'The image should be at least {min} pixels wide';

    const LABELED_MESSAGE = '{label} should be at least {min} pixels wide';

    protected array $options = [
        self::OPTION_MAX => 1000000,
        self::OPTION_MIN => 0,
    ];

    public function validate(mixed $value, string $valueIdentifier = null): bool
    {
        $this->value = $value;
        if (!file_exists($value)) {
            $this->success = false;
        } else {
            $imageInfo = getimagesize($value);
            $width = isset($imageInfo[0]) ? $imageInfo[0] : 0;
            $this->success = $width &&
                $width <= $this->options[self::OPTION_MAX] &&
                $width >= $this->options[self::OPTION_MIN];
        }

        return $this->success;
    }
}
