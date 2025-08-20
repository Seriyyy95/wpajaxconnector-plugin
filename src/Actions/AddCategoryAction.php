<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Actions;

use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\AbstractResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\BadRequestResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\InternalServerErrorResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PermissionDeniedResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\ShortCategoryResponse;

class AddCategoryAction extends AbstractAction
{
    public function getName(): string
    {
        return 'add_category';
    }

    public function handle(int $userId): AbstractResponse
    {
        if (!isset($_REQUEST["category_name"])) {
            return new BadRequestResponse();
        }

        if (!user_can($userId, 'edit_posts')) {
            return new PermissionDeniedResponse();
        }

        $categoryName = $_REQUEST["category_name"];
        $categorySlug = $_REQUEST["category_slug"];

        $id = category_exists($categoryName, 0);

        if ($id) {
            return new ShortCategoryResponse(categoryId: intval($id));
        }

        $categoryId = wp_insert_category(
            [
                'cat_name' => $categoryName,
//                'category_description' => '',
                'category_nicename' => $categorySlug,
                'taxonomy' => 'category'
            ]
        );

        if ($categoryId instanceof \WP_Error) {
            return new InternalServerErrorResponse($categoryId->get_error_message());
        }

        return new ShortCategoryResponse(categoryId: $categoryId);
    }
}