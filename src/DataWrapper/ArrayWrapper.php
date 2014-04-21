<?php

namespace Sirius\Validation\DataWrapper;

use Sirius\Validation\Util\Arr;

class ArrayWrapper implements WrapperInterface
{

    /**
     * @var array
     */
    protected $data = array();

    /**
     * @param array $data
     */
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
        return Arr::getByPath($this->data, $item);
    }

    function getItemsBySelector($selector)
    {
        return Arr::getBySelector($this->data, $selector);
    }

}
