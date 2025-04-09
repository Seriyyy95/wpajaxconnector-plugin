<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Actions;

use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\AbstractResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\AttachmentDataResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\AttachmentsListResponse;

class ListAttachmentsAction extends AbstractAction
{
    public function getName(): string
    {
        return 'list_attachments';
    }

    protected function handle(int $userId): AbstractResponse
    {
        $sort_values = [
            "date",
            "modified",
            "ID",
            "author",
            "parent",
            "comment_count",
            "menu_order",
            "relevance"
        ];
        $order_values = ["asc", "desc"];

        $count = isset($_REQUEST["count"]) ? $_REQUEST["count"] : 30;
        $page = isset($_REQUEST["page"]) ? $_REQUEST["page"] : 1;

        if (isset($_REQUEST["sort"]) && in_array($_REQUEST["sort"], $sort_values)) {
            $sort = $_REQUEST["sort"];
        } else {
            $sort = "relevance";
        }
        if (isset($_REQUEST["order"]) && in_array($_REQUEST["order"], $order_values)) {
            $order = $_REQUEST["order"];
        } else {
            $order = "desc";
        }
        $args = [
            'post_type' => 'attachment',
            'post_status' => 'inherit',
            'posts_per_page' => $count,
            'ignore_sticky_posts' => 1,
            'orderby' => $sort,
            'order' => $order,
            'paged' => $page,
        ];
        if (isset($_REQUEST["text"]) && strlen($_REQUEST["text"]) > 0) {
            $args["s"] = $_REQUEST["text"];
        }
        if (isset($_REQUEST['post_id'])) {
            $args['post_parent'] = $_REQUEST['post_id'];
        }
        if (isset($_REQUEST["start_date"]) && strlen($_REQUEST["start_date"]) > 0
            && isset($_REQUEST["end_date"]) && strlen($_REQUEST["end_date"]) > 0) {
            $args['date_query'] = array(
                [
                    'after' => $_REQUEST["start_date"],
                    'before' => $_REQUEST["end_date"],
                    'inclusive' => true,
                ]
            );
        }

        if (!user_can($userId, 'editor') || !user_can($userId, 'administrator')) {
            $args['post_author'] = $userId;
        }

        $result = new \WP_Query($args);
        $maxPages = intval($result->max_num_pages);
        $hasMore = $page < $maxPages;
        $postsArray = [];
        while ($result->have_posts()) {
            $result->the_post();
            $attachmentData = AttachmentDataResponse::fromAttachmentId($result->post->ID);

            $postsArray[] = $attachmentData->toArray();
        }

        if (count($postsArray) === 0) {
            return new AttachmentsListResponse([], false);
        }

        return new AttachmentsListResponse(
            attachments: $postsArray,
            hasMore: $hasMore,
        );
    }
}