<?php

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Responses;

class KeywordsResponse extends AbstractResponse
{
    public function __construct(
        private readonly int   $postId,
        private readonly array $keywords,
    ) {}

    public function toArray(): array
    {
        return [
            'data' => [
                'post_id' => $this->postId,
                'keywords' => $this->keywords,
            ]
        ];
    }
}