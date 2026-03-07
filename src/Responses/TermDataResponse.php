<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Responses;

class TermDataResponse extends AbstractResponse
{
    public function __construct(
        private readonly int    $id,
        private readonly string $slug,
        private readonly string $name,
        private readonly string $type,
        private readonly string $url,
    ) {}

    public function toArray(): array
    {
        return [
            'data' => [
                'id' => $this->id,
                'slug' => $this->slug,
                'name' => $this->name,
                'type' => $this->type,
                'url' => $this->url,
            ]
        ];
    }
}