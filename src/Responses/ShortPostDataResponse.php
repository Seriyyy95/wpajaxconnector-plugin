<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Responses;

class ShortPostDataResponse extends AbstractResponse
{
    public function __construct(
        private readonly int    $postId,
        private readonly string $url,
        private readonly string $title,
    ) {}

    public function toArray(): array
    {
        return [
            'post_id' => $this->postId,
            'url' => $this->url,
            'title' => $this->title,
        ];
    }
}