<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Actions;

use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\AbstractResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\BadRequestResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\InternalServerErrorResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PermissionDeniedResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PostIdResponse;

class SetPostContentAction extends AbstractAction
{
    public function getName(): string
    {
        return 'set_post_content';
    }

    public function handle(int $userId): AbstractResponse
    {
        if (!isset($_REQUEST["post_id"])) {
            return new BadRequestResponse();
        }
        if (!isset($_REQUEST["post_content"])) {
            return new BadRequestResponse();
        }
        $postId = $_REQUEST['post_id'] ?? null;
        $content = $_REQUEST['post_content'] ?? null;

        if (!user_can($userId, 'edit_post', $postId)) {
            return new PermissionDeniedResponse($postId);
        }

        $postData = [
            'ID' => $postId,
            'post_content' => $content,
        ];

        wp_update_post($postData);

        return new PostIdResponse(intval($postId));
    }
}