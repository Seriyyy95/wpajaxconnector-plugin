<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Actions;

use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\AbstractResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\BadRequestResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PermissionDeniedResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PostDataResponse;

class GetPostDataAction extends AbstractAction
{
    public function getName(): string
    {
        return 'get_post_data';
    }

    public function handle(int $userId): AbstractResponse
    {
        if (empty($_REQUEST["post_id"] ?? null)) {
            return new BadRequestResponse();
        }

        $post_id = intval($_REQUEST['post_id']);
        if (!user_can($userId, 'edit_post', $post_id)) {
            return new PermissionDeniedResponse($post_id);
        }

        $post = get_post($post_id);

        list($permalink, $postname) = get_sample_permalink($post_id);
        $url = str_replace('%postname%', $postname, $permalink);

        $category = get_the_category($post->ID)[0]->name;
        $tags = [];
        foreach (wp_get_post_tags($post->ID) as $tag) {
            $tags[] = $tag->name;
        }

        $author = get_the_author_meta('user_nicename', $post->post_author);

        $content = wpautop($post->post_content, false);
        $content = do_shortcode($content);

        return new PostDataResponse(
            postId: $post->ID,
            postTitle: $post->post_title,
            postContent: $content,
            postStatus: $post->post_status,
            postType: $post->post_type,
            postMimeType: $post->post_mime_type,
            postParent: $post->post_parent,
            publishDate: $post->post_date,
            lastModifiedDate: get_the_modified_date('Y-m-d H:i:s', $post->ID),
            postUrl: $url,
            categoryName: $category,
            tags: $tags,
            author: $author,
        );
    }
}