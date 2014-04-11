<?php

namespace Sirius\Validation;

interface ValidatableInterface
{

    function setValidator($validator);

    function getValidator($validator);

    function isValid();
}
