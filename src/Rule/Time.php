<?php

namespace Sirius\Validation\Rule;

class Time extends Date
{
    const MESSAGE = 'This input must be a time having the format {format}';
    const LABELED_MESSAGE = '{label} must be a time having the format {format}';

    protected $options = array(
        'format' => 'H:i:s'
    );
}
