<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Responses;

class TagResponse extends AbstractResponse
{
    public function __construct(
        private readonly int $tagId,
    ) {}

    public function toArray(): array
    {
        return [
            'data' => [
                'tag_id' => $this->tagId,
            ]
        ];
    }
}