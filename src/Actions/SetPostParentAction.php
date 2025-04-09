<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Actions;

use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\AbstractResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\BadRequestResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PermissionDeniedResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PostIdResponse;

class SetPostParentAction extends AbstractAction
{
    public function getName(): string
    {
        return 'set_post_parent';
    }

    public function handle(int $userId): AbstractResponse
    {
        if (!isset($_REQUEST["post_id"])) {
            return new BadRequestResponse();
        }
        if (!isset($_REQUEST["post_parent_id"])) {
            return new BadRequestResponse();
        }
        $postId = $_REQUEST['post_id'] ?? null;
        $postParentId = $_REQUEST['post_parent_id'] ?? null;

        if (!user_can($userId, 'edit_post', $postId)) {
            return new PermissionDeniedResponse($postId);
        }

        $postData = [
            'ID' => $postId,
            'post_parent' => $postParentId,
        ];

        wp_update_post($postData);

        return new PostIdResponse(intval($postId));
    }
}