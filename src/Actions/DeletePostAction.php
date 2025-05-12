<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Actions;

use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\AbstractResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\BadRequestResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\InternalServerErrorResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PermissionDeniedResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\SuccessResponse;

class DeletePostAction extends AbstractAction
{
    public function getName(): string
    {
        return 'delete_post';
    }

    public function handle(int $userId): AbstractResponse
    {
        if (!isset($_REQUEST["post_id"])) {
            return new BadRequestResponse();
        }
        if (!user_can($userId, 'edit_posts')) {
            return new PermissionDeniedResponse();
        }

        $postId = $_REQUEST["post_id"];
        $result = wp_delete_post($postId);

        if (false !== $result) {
            return new SuccessResponse();
        } else {
            return new InternalServerErrorResponse("");
        }
    }
}