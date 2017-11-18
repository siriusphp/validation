<?php
namespace Sirius\Validation\Rule\Upload;

use Sirius\Validation\Rule\AbstractRule;

class ImageRatio extends AbstractRule
{
    // the image width/height ration;
    // can be a number or a string like 4:3, 16:9
    const OPTION_RATIO = 'ratio';
    // how much can the image ratio diverge from the allowed ratio
    const OPTION_ERROR_MARGIN = 'error_margin';

    const MESSAGE = 'The image does must have a ratio (width/height) of {ratio})';

    const LABELED_MESSAGE = '{label} does must have a ratio (width/height) of {ratio})';

    protected $options = array(
        self::OPTION_RATIO        => 0,
        self::OPTION_ERROR_MARGIN => 0,
    );

    protected function normalizeRatio($ratio)
    {
        if (is_numeric($ratio) || $ratio == filter_var($ratio, FILTER_SANITIZE_NUMBER_FLOAT)) {
            return floatval($ratio);
        }
        if (strpos($ratio, ':') !== false) {
            list($width, $height) = explode(':', $ratio);

            return $width / $height;
        }

        return 0;
    }

    public function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;
        $ratio       = $this->normalizeRatio($this->options[self::OPTION_RATIO]);
        if (! is_array($value) || ! isset($value['tmp_name'])) {
            $this->success = false;
        } elseif (! file_exists($value['tmp_name'])) {
            $this->success = $value['error'] === UPLOAD_ERR_NO_FILE;
        } elseif ($ratio == 0) {
            $this->success = true;
        } else {
            $imageInfo     = getimagesize($value['tmp_name']);

            if (is_array($imageInfo)) {
                $actualRatio   = $imageInfo[0] / $imageInfo[1];
                $this->success = abs($actualRatio - $ratio) <= $this->options[self::OPTION_ERROR_MARGIN];
            } else {
                // no image size computed => no valid image
                return $this->success = false;
            }
        }

        return $this->success;
    }
}
