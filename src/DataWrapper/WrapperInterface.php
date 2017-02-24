<?php

namespace Sirius\Validation\DataWrapper;

interface WrapperInterface
{

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
