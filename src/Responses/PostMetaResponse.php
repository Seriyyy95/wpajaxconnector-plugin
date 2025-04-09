<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Responses;

class PostMetaResponse extends AbstractResponse
{
    public function __construct(
        private readonly string  $key,
        private readonly ?string $value,
    ) {}

    public function toArray(): array
    {
        return [
            'data' => [
                'key' => $this->key,
                'value' => $this->value,
            ]
        ];
    }
}