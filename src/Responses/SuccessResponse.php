<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Responses;

class SuccessResponse extends AbstractResponse
{
    public function toArray(): array
    {
        return [
            'success' => true,
        ];
    }
}