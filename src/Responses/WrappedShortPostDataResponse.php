<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Responses;

class WrappedShortPostDataResponse extends ShortPostDataResponse
{
    public function toArray(): array
    {
        return [
            "data" => parent::toArray(),
        ];
    }
}