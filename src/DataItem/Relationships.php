<?php

namespace JsonApi\DataItem;

use JsonApi\DataItem;
use JsonApi\IterableTrait;

class Relationships implements \Iterator
{
    use IterableTrait;

    /**
     * @var DataItem[]
     */
    private $items;


    /**
     * Relationships constructor.
     * @param array $relationships
     */
    public function __construct(array $relationships)
    {
        $this->items = array_filter(array_map(function ($item) {
            if (isset($item['data'])) {
                return new DataItem($item['data']);
            }
            //probably array of data
            foreach ($item as $v) {
                if (isset($v['data'])) {
                    return new DataItem($v['data']);
                }
            }
            return false;
        }, $relationships));
    }

    /**
     * @param string $type
     * @return Relationships
     */
    public function filterByType(string $type): self
    {
        $filtered = array_map(function (DataItem $data) use ($type) {
            if ($data->typeIs($type)) {
                return ['data' => $data->toArray()];
            }
            return [];
        }, $this->items);
        return new self(array_filter($filtered));
    }

    /**
     * @param string $param
     * @return array
     */
    public function pluck(string $param): array
    {
        return array_filter(array_map(function (DataItem $relationship) use ($param) {
            return $relationship->toArray()[$param] ?? null;
        }, $this->items));
    }

    /**
     * @return DataItem|null
     */
    public function first(): ?DataItem
    {
        return $this->items[0] ?? null;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array_map(function (DataItem $item) {
            return ['data' => $item->toArray()];
        }, $this->items);
    }

    /**
     * @return DataItem[]
     */
    public function items(): array
    {
        return $this->items;
    }
}
