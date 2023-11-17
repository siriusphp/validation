<?php
declare(strict_types=1);

namespace Sirius\Validation\Rule\Upload;

use Sirius\Validation\ErrorMessage;
use Sirius\Validation\Rule\AbstractRule;

class Image extends AbstractRule
{
    const OPTION_ALLOWED_IMAGES = 'allowed';

    const MESSAGE = 'The file is not a valid image (only {image_types} are allowed)';

    const LABELED_MESSAGE = '{label} is not a valid image (only {image_types} are allowed)';

    protected array $options = [
        self::OPTION_ALLOWED_IMAGES => ['jpg', 'png', 'gif']
    ];

    /**
     * @var array<int, string>
     */
    protected $imageTypesMap = [
        IMAGETYPE_GIF => 'gif',
        IMAGETYPE_JPEG => 'jpg',
        IMAGETYPE_JPEG2000 => 'jpg',
        IMAGETYPE_PNG => 'png',
        IMAGETYPE_PSD => 'psd',
        IMAGETYPE_BMP => 'bmp',
        IMAGETYPE_ICO => 'ico',
        IMAGETYPE_WEBP => 'webp',
    ];

    public function setOption(string $name, mixed $value): static
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

    public function validate(mixed $value, string $valueIdentifier = null): bool
    {
        $this->value = $value;
        if (!is_array($value) || !isset($value['tmp_name'])) {
            $this->success = false;
        } elseif (!file_exists($value['tmp_name'])) {
            $this->success = $value['error'] === UPLOAD_ERR_NO_FILE;
        } else {
            $imageInfo = getimagesize($value['tmp_name']);
            if (!is_array($imageInfo)) {
                $this->success = false;
            } else {
                $extension = $this->imageTypesMap[$imageInfo[2]] ?? false;
                $this->success = ($extension && in_array($extension, $this->options[self::OPTION_ALLOWED_IMAGES]));
            }
        }

        return $this->success;
    }

    public function getPotentialMessage(): ErrorMessage
    {
        $message = parent::getPotentialMessage();
        $imageTypes = array_map('strtoupper', $this->options[self::OPTION_ALLOWED_IMAGES]);
        $message->setVariables([
            'image_types' => implode(', ', $imageTypes)
        ]);

        return $message;
    }
}
