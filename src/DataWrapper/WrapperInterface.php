<?php

namespace Sirius\Validation\DataWrapper;

interface WrapperInterface
{

    /**
     * Set values to be used by this data wrapper
     *
     * @param array|\ArrayObject|object $data
     *
     * @throws \InvalidArgumentException
     */
    public function setData($data);

    /**
     * Get value from the data container using the path
     *
     * @param $item
     *
     * @return mixed
     */
    public function getItemValue($item);

    /**
     * Get items by selector
     *
     * @param $selector
     *
     * @return array
     */
    public function getItemsBySelector($selector);

}
