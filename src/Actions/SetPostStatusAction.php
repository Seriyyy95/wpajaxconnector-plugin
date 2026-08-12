<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Actions;

use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\AbstractResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\BadRequestResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\InternalServerErrorResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PermissionDeniedResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PostIdResponse;

class SetPostStatusAction extends AbstractAction
{
    public function getName(): string
    {
        return 'set_post_status';
    }

    public function handle(int $userId): AbstractResponse
    {
        if (!isset($_REQUEST["post_id"])) {
            return new BadRequestResponse();
        }
        if (!isset($_REQUEST["post_status"])) {
            return new BadRequestResponse();
        }
        if (!user_can($userId, 'edit_posts')) {
            return new PermissionDeniedResponse();
        }

        $postId = $_REQUEST['post_id'] ?? null;
        $status = $_REQUEST['post_status'] ?? null;

        if (false === in_array($status, ['publish', 'draft', 'pending'])) {
            return new BadRequestResponse();
        }

        $postData = [
            'ID' => $postId,
            'post_status' => $status,
        ];

        wp_update_post($postData);

        return new PostIdResponse(intval($postId));
    }
}