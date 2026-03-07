<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Actions;

use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\AbstractResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\BadRequestResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\InternalServerErrorResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\NotFoundResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PermissionDeniedResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PostIdResponse;

class SetPostAuthorAction extends AbstractAction
{
    public function getName(): string
    {
        return 'set_post_author';
    }

    public function handle(int $userId): AbstractResponse
    {
        if (!isset($_REQUEST["post_id"])) {
            return new BadRequestResponse();
        }
        if (!isset($_REQUEST["post_author"])) {
            return new BadRequestResponse();
        }
        if (!user_can($userId, 'edit_posts')) {
            return new PermissionDeniedResponse();
        }

        $postId = $_REQUEST['post_id'] ?? null;
        $authorSlug = $_REQUEST['post_author'] ?? null;

        $userId = get_user_by('slug', $authorSlug);

        if (null === $userId) {
            return new NotFoundResponse();
        }

        $postData = [
            'ID' => $postId,
            'post_author' => $userId,
        ];

        wp_update_post($postData);

        return new PostIdResponse(intval($postId));
    }
}