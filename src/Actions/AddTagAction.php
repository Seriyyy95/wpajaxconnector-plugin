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

        if (!isset($_REQUEST["tag_slug"])) {
            return new BadRequestResponse();
        }

        if (!user_can($userId, 'edit_posts')) {
            return new PermissionDeniedResponse();
        }

        $tagName = $_REQUEST["tag_name"];
        $tagSlug = $_REQUEST["tag_slug"];

        $tag = get_term_by('slug', $tagSlug, 'post_tag');
        $id = $tag?->term_id;

        if ($id) {
            return new TagResponse(
                tagId: intval($id),
                url: get_tag_link($id)
            );
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

        return new TagResponse(
            tagId: intval($termData['term_id']),
            url: get_tag_link($termData['term_id']),
        );
    }
}