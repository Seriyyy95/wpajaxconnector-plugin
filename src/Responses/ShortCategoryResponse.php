<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Responses;

class ShortCategoryResponse extends AbstractResponse
{
    public function __construct(
        private readonly int $categoryId,
    ) {}

    public function toArray(): array
    {
        return [
            'data' => [
                'category_id' => $this->categoryId,
            ]
        ];
    }
}