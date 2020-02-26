<?php
declare(strict_types=1);

namespace Sirius\Validation\DataWrapper;

interface WrapperInterface
{

    /**
     * Get value from the data container using the path
     *
     * @param string $item
     *
     * @return mixed
     */
    public function getItemValue(string $item);

    /**
     * Get items by selector
     *
     * @param string $selector
     *
     * @return array
     */
    public function getItemsBySelector(string $selector);
}
