<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2017. 03. 07.
 * Time: 16:02
 */

namespace Sirius\Validation\Rule\Upload;

use Sirius\Validation\Rule\AbstractRule;

class Required extends AbstractRule
{
    const MESSAGE = 'The file is required';

    const LABELED_MESSAGE = '{label} is required';

    public function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;
        if (! is_array($value) || ! isset($value['tmp_name']) ||
            ! file_exists($value['tmp_name']) || $value['error'] !== UPLOAD_ERR_OK) {
            $this->success = false;
        } else {
            $this->success = true;
        }

        return $this->success;
    }
}
