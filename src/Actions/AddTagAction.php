<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Actions;

use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\AbstractResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\BadRequestResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\InternalServerErrorResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PermissionDeniedResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\TagResponse;

class AddTagAction extends AbstractAction
{
    public function getName(): string
    {
        return 'add_tag';
    }

    public function handle(int $userId): AbstractResponse
    {
        if (!isset($_REQUEST["tag_name"])) {
            return new BadRequestResponse();
        }

        if (!user_can($userId, 'edit_posts')) {
            return new PermissionDeniedResponse();
        }

        $tagName = $_REQUEST["tag_name"];
        $lowerTagName = mb_strtolower($_REQUEST["tag_name"]);
        $upperTagName = mb_strtoupper($_REQUEST["tag_name"]);
        $tagSlug = $_REQUEST["tag_slug"];

        $existsData = tag_exists($tagName);
        if ($existsData) {
            return new TagResponse(tagId: intval($existsData['term_id']));
        }

        //Also check if there is a tag with lower and upper case
        $existsLowerData = tag_exists($lowerTagName);
        if ($existsLowerData) {
            return new TagResponse(tagId: intval($existsLowerData['term_id']));
        }

        $existsUpperData = tag_exists($upperTagName);
        if ($existsUpperData) {
            return new TagResponse(tagId: intval($existsUpperData['term_id']));
        }

        $termData = wp_insert_term(
            $tagName,
            'post_tag',
            [
//                'description'=> '',
                'slug' => $tagSlug,
            ]
        );

        if ($termData instanceof \WP_Error) {
            return new InternalServerErrorResponse($termData->get_error_message());
        }

        return new TagResponse(tagId: intval($termData['term_id']));
    }
}