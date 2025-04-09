<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Responses;

class BadRequestResponse extends AbstractResponse
{
    public function getCode(): int
    {
        return 400;
    }

    public function toArray(): array
    {
        return [
            'error' => 'bad request',
        ];
    }
}