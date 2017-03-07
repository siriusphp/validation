<?php

namespace Sirius\Validation\Rule\Upload;

use Sirius\Validation\Rule\AbstractRule;

class Extension extends AbstractRule
{
    const OPTION_ALLOWED_EXTENSIONS = 'allowed';

    const MESSAGE = 'The file does not have an acceptable extension ({file_extensions})';

    const LABELED_MESSAGE = '{label} does not have an acceptable extension ({file_extensions})';

    protected $options = array(
        self::OPTION_ALLOWED_EXTENSIONS => array()
    );

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

    public function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;
        if (! is_array($value) || ! isset($value['tmp_name'])) {
            $this->success = false;
        } elseif (! file_exists($value['tmp_name'])) {
            $this->success = $value['error'] === UPLOAD_ERR_NO_FILE;
        } else {
            $extension     = strtolower(substr($value['name'], strrpos($value['name'], '.') + 1, 10));
            $this->success = is_array($this->options[self::OPTION_ALLOWED_EXTENSIONS]) && in_array(
                $extension,
                $this->options[self::OPTION_ALLOWED_EXTENSIONS]
            );
        }

        return $this->success;
    }

    public function getPotentialMessage()
    {
        $message        = parent::getPotentialMessage();
        $fileExtensions = array_map('strtoupper', $this->options[self::OPTION_ALLOWED_EXTENSIONS]);
        $message->setVariables(
            array(
                'file_extensions' => implode(', ', $fileExtensions)
            )
        );

        return $message;
    }
}
