<?php

namespace Sirius\Validation;

interface ValidatorInterface
{

    function add($selector, $name = null, $options = null, $messageTemplate = null, $label = null);

    function remove($selector, $name = true, $options = null);

    function validate($data = array());
}
