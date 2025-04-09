<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Responses;

abstract class AbstractResponse
{
    public abstract function toArray(): array;

    public function getCode(): int
    {
        return 200;
    }
}