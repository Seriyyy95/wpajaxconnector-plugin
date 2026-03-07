<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Actions;

use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\AbstractResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\BadRequestResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PermissionDeniedResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PostIdResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PostMetaResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\TermDataResponse;

class GetTermDataAction extends AbstractAction
{
    public function getName(): string
    {
        return 'get_term_data';
    }

    public function handle(int $userId): AbstractResponse
    {
        if (!isset($_REQUEST["term_id"])) {
            return new BadRequestResponse();
        }
        $termId = $_REQUEST["term_id"];
        if (!user_can($userId, 'edit_posts')) {
            return new PermissionDeniedResponse();
        }

        $term = get_term($termId);
        $termLink = get_term_link($termId, $term->slug);

        return new TermDataResponse(
            id: $term->ID,
            slug: $term->slug,
            name: $term->name,
            type: $term->taxonomy,
            url: $termLink,
        );
    }
}