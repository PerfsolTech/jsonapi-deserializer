<?php

namespace Perfsol\JsonApi;

class JsonApi
{
    /**
     * @var array
     */
    private $json;

    /**
     * JsonApi constructor.
     * @param array|string $json
     */
    public function __construct($json)
    {
        if (is_string($json)) {
            $json = json_decode($json, true);
        }
        $this->json = $json;
    }

    /**
     * @return Data
     */
    public function data(): Data
    {
        return new Data($this->json['data'] ?? []);
    }

    public function toArray(): array
    {
        return [
            'data' => $this->data()->toArray(),
            'filter' => $this->filter()
        ];
    }

    /**
     * @param string|null $name
     * @return array|mixed|null
     */
    public function filter(string $name = null)
    {
        $filters = $this->json['filter'] ?? [];
        return $name ? ($filters[$name] ?? null) : $filters;
    }
}
