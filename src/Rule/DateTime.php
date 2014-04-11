<?php

namespace Sirius\Validation\Rule;

class DateTime extends Date
{

    protected static $defaultMessageTemplate = 'This input must be a date having the format {format}';

    protected $options = array(
        'format' => 'Y-m-d H:i:s'
    );

}
