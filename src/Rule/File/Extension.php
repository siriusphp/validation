<?php
declare(strict_types=1);

namespace Sirius\Validation\Rule\File;

use Sirius\Validation\ErrorMessage;
use Sirius\Validation\Rule\AbstractRule;

class Extension extends AbstractRule
{
    const OPTION_ALLOWED_EXTENSIONS = 'allowed';

    const MESSAGE = 'The file does not have an acceptable extension ({file_extensions})';

    const LABELED_MESSAGE = '{label} does not have an acceptable extension ({file_extensions})';

    protected $options = [
        self::OPTION_ALLOWED_EXTENSIONS => []
    ];

    public function setOption($name, $value)
    {
        if ($name == self::OPTION_ALLOWED_EXTENSIONS) {
            if (is_string($value)) {
                $value = explode(',', $value);
            }
            $value = array_map('trim', $value);
            $value = array_map('strtolower', $value);
        }

        return parent::setOption($name, $value);
    }

    public function validate($value, string $valueIdentifier = null):bool
    {
        $this->value = $value;
        if (! file_exists($value)) {
            $this->success = false;
        } else {
            $extension     = strtolower(substr($value, strrpos($value, '.') + 1, 10));
            $this->success = is_array($this->options[self::OPTION_ALLOWED_EXTENSIONS]) && in_array(
                $extension,
                $this->options[self::OPTION_ALLOWED_EXTENSIONS]
            );
        }

        return $this->success;
    }

    public function getPotentialMessage():ErrorMessage
    {
        $message        = parent::getPotentialMessage();
        $fileExtensions = array_map('strtoupper', $this->options[self::OPTION_ALLOWED_EXTENSIONS]);
        $message->setVariables([
            'file_extensions' => implode(', ', $fileExtensions)
        ]);

        return $message;
    }
}
