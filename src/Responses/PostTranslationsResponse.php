<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Responses;

class PostTranslationsResponse extends AbstractResponse
{
    public function __construct(
        private readonly array $translations
    ) {}

    public function toArray(): array
    {
        return [
            'data' => $this->translations
        ];
    }
}