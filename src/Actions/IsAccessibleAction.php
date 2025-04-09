<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Actions;

use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\AbstractResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\SuccessResponse;

class IsAccessibleAction extends AbstractAction
{
    public function getName(): string
    {
        return 'is_accessible';
    }

    protected function handle(int $userId): AbstractResponse
    {
        return new SuccessResponse();
    }
}