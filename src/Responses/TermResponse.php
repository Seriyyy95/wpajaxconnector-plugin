<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Responses;

class TermResponse extends AbstractResponse
{
    public function __construct(
        private readonly int $termId,
    ) {}

    public function toArray(): array
    {
        return [
            'data' => [
                'term_id' => $this->termId,
            ]
        ];
    }
}