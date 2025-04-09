<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Actions;

use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\AbstractResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\BadRequestResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PermissionDeniedResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\WrappedShortPostDataResponse;

class AddPostAction extends AbstractAction
{
    public function getName(): string
    {
        return 'add_post';
    }

    public function handle(int $userId): AbstractResponse
    {
        if (!isset($_REQUEST["post_title"])) {
            return new BadRequestResponse();
        }
        if (!user_can($userId, 'edit_posts')) {
            return new PermissionDeniedResponse();
        }

        $postContent = $_REQUEST["post_content"] ?? '';
        $postTitle = $_REQUEST["post_title"];

        $post_data = array(
            'post_title' => wp_strip_all_tags($postTitle),
            'post_content' => $postContent,
            'post_status' => 'draft',
            'post_author' => $userId,
        );

        $post_id = wp_insert_post($post_data);

        list($permalink, $postname) = get_sample_permalink($post_id);
        $url = str_replace('%postname%', $postname, $permalink);

        return new WrappedShortPostDataResponse(
            postId: $post_id,
            url: $url,
            title: $postTitle,
        );
    }
}