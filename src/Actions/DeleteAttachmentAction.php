<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Actions;

use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\AbstractResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\BadRequestResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PermissionDeniedResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\SuccessResponse;

class DeleteAttachmentAction extends AbstractAction
{
    public function getName(): string
    {
        return 'delete_attachment';
    }

    protected function handle(int $userId): AbstractResponse
    {
        if (!isset($_REQUEST["attachment_id"])) {
            return new BadRequestResponse();
        }
        if (!user_can($userId, 'edit_posts')) {
            return new PermissionDeniedResponse();
        }

        $attachmentId = $_POST["attachment_id"] ?? null;
        $result = wp_delete_attachment($attachmentId);

        return new SuccessResponse();
    }
}