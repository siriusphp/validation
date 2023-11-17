<?php
declare(strict_types=1);

namespace Sirius\Validation\Rule\File;

use Sirius\Validation\Rule\AbstractRule;
use Sirius\Validation\Util\RuleHelper;

class ImageRatio extends AbstractRule
{
    // the image width/height ration;
    // can be a number or a string like 4:3, 16:9
    const OPTION_RATIO = 'ratio';
    // how much can the image ratio diverge from the allowed ratio
    const OPTION_ERROR_MARGIN = 'error_margin';

    const MESSAGE = 'The image does must have a ratio (width/height) of {ratio})';

    const LABELED_MESSAGE = '{label} does must have a ratio (width/height) of {ratio})';

    protected array $options = [
        self::OPTION_RATIO => 0,
        self::OPTION_ERROR_MARGIN => 0,
    ];

    protected function normalizeRatio(mixed $ratio): float
    {
        if (is_numeric($ratio) || $ratio == filter_var($ratio, FILTER_SANITIZE_NUMBER_FLOAT)) {
            return floatval($ratio);
        }
        if (strpos($ratio, ':') !== false) {
            list($width, $height) = explode(':', $ratio);

            return (float) $width / (float) $height;
        }

        return 0;
    }

    public function validate(mixed $value, string $valueIdentifier = null): bool
    {
        $this->value = $value;
        $ratio = RuleHelper::normalizeImageRatio($this->options[self::OPTION_RATIO]);
        if (!file_exists($value)) {
            $this->success = false;
        } elseif ($ratio == 0) {
            $this->success = true;
        } else {
            $imageInfo = getimagesize($value);

            if (is_array($imageInfo)) {
                $actualRatio = $imageInfo[0] / $imageInfo[1];
                $this->success = abs($actualRatio - $ratio) <= $this->options[self::OPTION_ERROR_MARGIN];
            } else {
                // no image size computed => no valid image
                return $this->success = false;
            }
        }

        return $this->success;
    }
}
