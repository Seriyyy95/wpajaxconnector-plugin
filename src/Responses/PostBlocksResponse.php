<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Responses;

class PostBlocksResponse extends AbstractResponse
{
    public function __construct(
        private readonly int   $postId,
        private readonly array $blocks,
    ) {}

    public function toArray(): array
    {
        return [
            'data' => [
                'post_id' => $this->postId,
                'blocks' => $this->blocks,
            ]
        ];
    }
}