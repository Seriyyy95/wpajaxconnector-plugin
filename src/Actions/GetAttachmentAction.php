<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Actions;

use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\AbstractResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\BadRequestResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\NotFoundResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PermissionDeniedResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\WrappedAttachmentDataResponse;

class GetAttachmentAction extends AbstractAction
{
    public function getName(): string
    {
        return 'get_attachment';
    }

    public function handle(int $userId): AbstractResponse
    {
        if (!isset($_REQUEST["attachment_id"]) && !isset($_REQUEST['url'])) {
            return new BadRequestResponse();
        }
        if (!user_can($userId, 'edit_posts')) {
            return new PermissionDeniedResponse();
        }

        $attachmentId = $_REQUEST["attachment_id"] ?? null;
        $url = $_REQUEST['url'] ?? null;

        if (null === $attachmentId || null !== $url) {
            $attachmentId = attachment_url_to_postid($url);
        }

        if (false === wp_attachment_is('image', $attachmentId) && false === wp_attachment_is('video', $attachmentId)) {
            return new NotFoundResponse();
        }

        return WrappedAttachmentDataResponse::fromAttachmentId(intval($attachmentId));
    }
}