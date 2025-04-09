<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Actions;

use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\AbstractResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\BadRequestResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PermissionDeniedResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PostIdResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PostMetaResponse;

class GetPostMetaAction extends AbstractAction
{
    public function getName(): string
    {
        return 'get_post_meta';
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

        if (false === isset($_REQUEST["meta_key"])) {
            return new BadRequestResponse();
        }

        $metaKey = $_REQUEST["meta_key"];
        $value = get_post_meta($postId, $metaKey, true);

        if (empty($value)) {
            return new PostMetaResponse(
                key: $metaKey,
                value: null
            );
        }

        return new PostMetaResponse(
            key: $metaKey,
            value: $value
        );
    }
}