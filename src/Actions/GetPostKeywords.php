<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Actions;

use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\AbstractResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\BadRequestResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\KeywordsResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PermissionDeniedResponse;

class GetPostKeywords extends AbstractAction
{
    public function getName(): string
    {
        return 'get_post_keywords';
    }

    public function handle(int $userId): AbstractResponse
    {
        if (empty($_REQUEST["post_id"] ?? null)) {
            return new BadRequestResponse();
        }

        $postId = intval($_REQUEST['post_id']);
        if (!user_can($userId, 'edit_post', $postId)) {
            return new PermissionDeniedResponse($postId);
        }

        $meta = get_post_meta($postId, "seo_keywords", true);
        if ($meta !== false) {
            $keywords = explode(',', $meta);
            $keywordsArray = array_map('trim', $keywords);
        } else {
            $keywordsArray = [];
        }

        return new KeywordsResponse($postId, $keywordsArray);
    }
}