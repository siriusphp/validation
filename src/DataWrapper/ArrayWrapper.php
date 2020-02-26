<?php
declare(strict_types=1);

namespace Sirius\Validation\DataWrapper;

use Sirius\Validation\Util\Arr;
use Sirius\Validation\DataWrapper\WrapperInterface;

class ArrayWrapper implements WrapperInterface
{

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param array|\ArrayObject|object $data
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($data = [])
    {
        if (is_object($data)) {
            if ($data instanceof \ArrayObject) {
                $data = $data->getArrayCopy();
            } elseif (method_exists($data, 'toArray')) {
                $data = $data->toArray();
            }
        }
        if (! is_array($data)) {
            throw new \InvalidArgumentException('Data passed to validator is not an array or an ArrayObject');
        }
        $this->data = $data;
    }

    public function getItemValue(string $item)
    {
        return Arr::getByPath($this->data, $item);
    }

    public function getItemsBySelector(string $selector): array
    {
        return Arr::getBySelector($this->data, $selector);
    }
}
