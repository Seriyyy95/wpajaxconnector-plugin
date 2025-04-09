<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Actions;

use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\AbstractResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\BadRequestResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PermissionDeniedResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PostBlocksResponse;

class GetPostBlocksAction extends AbstractAction
{
    public function getName(): string
    {
        return 'get_post_blocks';
    }

    public function handle(int $userId): AbstractResponse
    {
        if (!isset($_REQUEST["post_id"])) {
            return new BadRequestResponse();
        }
        $post_id = $_REQUEST["post_id"];
        if (!user_can($userId, 'edit_post', $post_id)) {
            return new PermissionDeniedResponse($post_id);
        }

        $post_id = intval($_REQUEST['post_id']);
        $post = get_post($post_id);

        $blocks = parse_blocks($post->post_content);

        return new PostBlocksResponse(
            postId: $post->ID,
            blocks: $blocks
        );
    }
}