<?php

namespace Sirius\Validation\DataWrapper;

use Sirius\Validation\Utils;

class ArrayWrapper implements WrapperInterface
{
    protected $data = array();

    function __construct($data = array())
    {
        $this->setData($data);
    }

    function setData($data)
    {
        if (is_object($data)) {
            if ($data instanceof \ArrayObject) {
                $data = $data->getArrayCopy();
            } elseif (method_exists($data, 'toArray')) {
                $data = $data->toArray();
            }
        }
        if (!is_array($data)) {
            throw new \InvalidArgumentException('Data passed to validator is not an array or an ArrayObject');
        }
        $this->data = $data;
        return;
    }

    function getItemValue($item)
    {
        return Utils::arrayGetByPath($this->data, $item);
    }

    function getItemsBySelector($selector)
    {
        return Utils::arrayGetBySelector($this->data, $selector);
    }

}
