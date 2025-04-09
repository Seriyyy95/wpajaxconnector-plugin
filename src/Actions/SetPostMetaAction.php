<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Actions;

use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\AbstractResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\BadRequestResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\InternalServerErrorResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PermissionDeniedResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PostIdResponse;

class SetPostMetaAction extends AbstractAction
{
    public function getName(): string
    {
        return 'set_post_meta';
    }

    public function handle(int $userId): AbstractResponse
    {
        if (!isset($_REQUEST["post_id"])) {
            return new BadRequestResponse();
        }
        $post_id = $_REQUEST["post_id"];
        if (!user_can($userId, 'edit_posts')) {
            return new PermissionDeniedResponse();
        }

        if (isset($_POST["post_meta"])) {
            $postMeta = json_decode(stripslashes($_POST["post_meta"]), true);
        } else {
            $postMeta = array();
        }

        foreach ($postMeta as $meta_key => $meta_value) {
            if (is_array($meta_value)) {
                $meta_value = json_encode($meta_value, JSON_UNESCAPED_UNICODE);
            }
            delete_post_meta($post_id, $meta_key);
            add_post_meta($post_id, $meta_key, $meta_value);
        }

        return new PostIdResponse(intval($post_id));
    }
}