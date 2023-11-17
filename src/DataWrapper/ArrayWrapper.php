<?php
declare(strict_types=1);

namespace Sirius\Validation\DataWrapper;

use Sirius\Validation\Util\Arr;

class ArrayWrapper implements WrapperInterface
{

    /**
     * @var array<string,mixed>
     */
    protected array $data = [];

    /**
     * @param array<string,mixed>|\ArrayObject|object $data
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(mixed $data)
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
    }

    public function getItemValue(string $item): mixed
    {
        return Arr::getByPath($this->data, $item);
    }

    /**
     * @return array<string,mixed>
     */
    public function getItemsBySelector(string $selector): array
    {
        return Arr::getBySelector($this->data, $selector);
    }

}
