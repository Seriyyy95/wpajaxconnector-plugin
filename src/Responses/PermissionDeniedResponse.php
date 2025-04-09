<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Responses;

class PermissionDeniedResponse extends AbstractResponse
{
    public function __construct(
        private readonly ?int $postId = null
    ) {}

    public function getCode(): int
    {
        return 401;
    }

    public function toArray(): array
    {
        return [
            'error' => 'permission denied for post: ' . $this->postId,
        ];
    }
}