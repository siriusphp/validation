<?php
declare(strict_types=1);

namespace Sirius\Validation\DataWrapper;

interface WrapperInterface
{

    /**
     * Get value from the data container using the path
     *
     * @return mixed
     */
    public function getItemValue(string $item);

    /**
     * Get items by selector
     *
     * @return array<string,mixed>
     */
    public function getItemsBySelector(string $selector): array;
}
