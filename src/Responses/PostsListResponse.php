<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Responses;

class PostsListResponse extends AbstractResponse
{
    public function __construct(
        private readonly array $posts,
        private readonly bool  $hasMore
    ) {}

    public function toArray(): array
    {
        return [
            'posts' => $this->posts,
            'has_more' => $this->hasMore
        ];
    }
}