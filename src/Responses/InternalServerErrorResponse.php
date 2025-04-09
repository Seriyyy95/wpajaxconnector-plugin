<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Responses;

class InternalServerErrorResponse extends AbstractResponse
{
    public function __construct(private readonly string $message) {}

    public function getCode(): int
    {
        return 500;
    }

    public function toArray(): array
    {
        return [
            'error' => $this->message,
        ];
    }
}