<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Responses;

class PostIdResponse extends AbstractResponse
{
    public function __construct(private readonly int $postId) {}

    public function toArray(): array
    {
        return [
            'data' => [
                'post_id' => $this->postId,
            ]
        ];
    }
}