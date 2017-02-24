<?php

namespace Sirius\Validation;

interface ValidatorInterface
{
    public function add($selector, $name = null, $options = null, $messageTemplate = null, $label = null);

    public function remove($selector, $name = true, $options = null);

    public function validate($data = array());
}
