<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Actions;

use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\AbstractResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\BadRequestResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PermissionDeniedResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\WrappedAttachmentDataResponse;

class GetPostThumbnailAction extends AbstractAction
{
    public function getName(): string
    {
        return 'get_post_thumbnail';
    }

    public function handle(int $userId): AbstractResponse
    {
        if (!isset($_REQUEST["post_id"])) {
            return new BadRequestResponse();
        }

        $postId = $_REQUEST["post_id"];
        if (!user_can($userId, 'edit_posts')) {
            return new PermissionDeniedResponse();
        }

        $post = get_post($postId);
        $thumbnailId = get_post_thumbnail_id($post);

        return WrappedAttachmentDataResponse::fromAttachmentId(intval($thumbnailId));
    }
}