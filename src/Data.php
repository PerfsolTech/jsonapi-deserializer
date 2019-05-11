<?php

namespace Perfsol\JsonApi;

class Data implements \Iterator
{
    use IterableTrait;

    /**
     * @var DataItem[]
     */
    private $items;

    /**
     * Data constructor.
     * @param array|string $data
     */
    public function __construct($data)
    {
        if (is_string($data)) {
            $data = json_decode($data, true);
        }
        $this->items = array_map(function ($item) {
            return new DataItem($item);
        }, $data);
    }

    /**
     * @param string $type
     * @return Data
     */
    public function filterByType(string $type): self
    {
        $filtered = (array_map(function (DataItem $dataItem) use ($type) {
            if ($dataItem->typeIs($type)) {
                return $dataItem->toArray();
            }
            return [];
        }, $this->items));
        return new self(array_filter($filtered));
    }

    /**
     * @return  DataItem[]
     */
    public function items(): array
    {
        return $this->items;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array_map(function (DataItem $item) {
            return $item->toArray();
        }, $this->items);
    }
}
