<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Actions;

use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\AbstractResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\BadRequestResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PermissionDeniedResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PostIdResponse;

class SetPostTagsAction extends AbstractAction
{
    public function getName(): string
    {
        return 'set_post_tags';
    }

    public function handle(int $userId): AbstractResponse
    {
        if (!isset($_REQUEST["post_id"])) {
            return new BadRequestResponse();
        }
        if (!isset($_REQUEST["tag_names"])) {
            return new BadRequestResponse();
        }
        if (!user_can($userId, 'edit_posts')) {
            return new PermissionDeniedResponse();
        }

        $postId = $_REQUEST['post_id'] ?? null;
        $tagNames = $_REQUEST['tag_names'] ?? [];

        wp_set_post_tags($postId, $tagNames, false);

        return new PostIdResponse(intval($postId));
    }
}