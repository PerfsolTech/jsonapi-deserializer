<?php

namespace Perfsol\JsonApi;

use Perfsol\JsonApi\DataItem\Relationships;

class DataItem
{
    private $dataItem;

    /**
     * DataItem constructor.
     * @param array|string $dataItem
     */
    public function __construct($dataItem)
    {
        if (is_string($dataItem)) {
            $dataItem = json_decode($dataItem, true);
        }
        $this->dataItem = $dataItem;
    }

    public function typeIs($type): bool
    {
        return $this->type === $type;
    }

    public function attributes(): array
    {
        return $this->dataItem['attributes'] ?? [];
    }

    public function relationships(string $type = null): Relationships
    {
        $relationships = new Relationships(isset($this->dataItem['relationships']) ? array_values($this->dataItem['relationships']) : []);
        return $type ? $relationships->filterByType($type) : $relationships;
    }

    public function id()
    {
        return $this->id;
    }

    public function type()
    {
        return $this->type;
    }

    public function delete(): bool
    {
        return $this->delete ?? false;
    }

    public function __get($attr)
    {
        return $this->dataItem[$attr] ?? null;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id(),
            'type' => $this->type(),
            'delete' => $this->delete(),
            'attributes' => $this->attributes(),
            'relationships' => $this->relationships()->toArray(),
        ];
    }
}
