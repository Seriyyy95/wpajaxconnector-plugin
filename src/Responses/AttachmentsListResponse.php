<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Responses;

class AttachmentsListResponse extends AbstractResponse
{
    public function __construct(
        private readonly array $attachments,
        private readonly bool  $hasMore
    ) {}

    public function toArray(): array
    {
        return [
            'attachments' => $this->attachments,
            'has_more' => $this->hasMore
        ];
    }
}