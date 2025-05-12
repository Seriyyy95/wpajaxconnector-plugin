<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Responses;

class UnprocessableEntityErrorResponse extends AbstractResponse
{
    public function __construct(
        private string $message
    ) {}

    public function getCode(): int
    {
        return 412;
    }

    public function toArray(): array
    {
        return [
            'error' => $this->message,
        ];
    }
}