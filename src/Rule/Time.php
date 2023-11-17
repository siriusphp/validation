<?php
declare(strict_types=1);

namespace Sirius\Validation\Rule;

class Time extends Date
{
    const MESSAGE = 'This input must be a time having the format {format}';
    const LABELED_MESSAGE = '{label} must be a time having the format {format}';

    protected array $options = [
        'format' => 'H:i:s'
    ];
}
