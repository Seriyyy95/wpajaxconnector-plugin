<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Responses;

class AccessDeniedResponse extends AbstractResponse
{
    public function getCode(): int
    {
        return 403;
    }

    public function toArray(): array
    {
        return [
            'error' => 'access denied'
        ];
    }
}