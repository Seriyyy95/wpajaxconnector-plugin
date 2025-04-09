<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Responses;

class NotFoundResponse extends AbstractResponse
{
    public function getCode(): int
    {
        return 404;
    }

    public function toArray(): array
    {
        return [
            'error' => 'not found',
        ];
    }
}