<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Actions;

use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\AbstractResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\BadRequestResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PermissionDeniedResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\SuccessResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Utils\AttachmentFinder;

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

        $url = wp_get_attachment_url($attachmentId);

        $attachments = AttachmentFinder::findAttachmentsByUrl($url);

        if (count($attachments) === 1) {
            $result = wp_delete_attachment($attachmentId);
        } else {
            global $wpdb;

            $wpdb->query(
                $wpdb->prepare(
                    "DELETE FROM $wpdb->posts, $wpdb->postmeta WHERE $wpdb->posts.ID = %d OR $wpdb->postmeta.post_id = %d",
                    $attachmentId,
                    $attachmentId
                )
            );
        }
        return new SuccessResponse();
    }
}