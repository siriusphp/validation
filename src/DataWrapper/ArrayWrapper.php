<?php

namespace Sirius\Validation\DataWrapper;

use Sirius\Validation\Util\Arr;
use Sirius\Validation\DataWrapper\WrapperInterface;

class ArrayWrapper implements WrapperInterface
{

    /**
     * @var array
     */
    protected $data = array();

    /**
     * @param array $data
     */
    public function __construct($data = array())
    {
        $this->setData($data);
    }

    /**
     * @param array|\ArrayObject|object $data
     * @throws \InvalidArgumentException
     */
    public function setData($data)
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

    public function getItemValue($item)
    {
        return Arr::getByPath($this->data, $item);
    }

    public function getItemsBySelector($selector)
    {
        return Arr::getBySelector($this->data, $selector);
    }

}
