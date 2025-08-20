<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Actions;

use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\AbstractResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\BadRequestResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\InternalServerErrorResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PermissionDeniedResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\SuccessResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\TermResponse;

class SetTermNameAction extends AbstractAction
{
    public function getName(): string
    {
        return 'set_term_name';
    }

    public function handle(int $userId): AbstractResponse
    {
        if (!isset($_REQUEST["term_id"])) {
            return new BadRequestResponse();
        }
        if (!isset($_REQUEST["taxonomy"])) {
            return new BadRequestResponse();
        }
        if (!isset($_REQUEST["term_name"])) {
            return new BadRequestResponse();
        }
        if (!user_can($userId, 'edit_posts')) {
            return new PermissionDeniedResponse();
        }

        $termId = $_REQUEST['term_id'] ?? null;
        $taxonomy = $_REQUEST['taxonomy'] ?? null;
        $name = $_REQUEST['term_name'] ?? null;

        $termData = [
            'name' => wp_strip_all_tags($name),
        ];

        $result = wp_update_term($termId, $taxonomy, $termData);

        if ($result instanceof \WP_Error) {
            return new InternalServerErrorResponse($result->get_error_message());
        }

        return new TermResponse($result['term_id']);
    }
}