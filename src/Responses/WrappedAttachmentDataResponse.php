<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Responses;

class WrappedAttachmentDataResponse extends AttachmentDataResponse
{
    public function toArray(): array
    {
        return [
            "data" => parent::toArray(),
        ];
    }
}