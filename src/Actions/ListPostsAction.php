<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Actions;

use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\AbstractResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PostsListResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\ShortPostDataResponse;

class ListPostsAction extends AbstractAction
{
    public function getName(): string
    {
        return 'list_posts';
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
        $type = isset($_REQUEST["type"]) ? $_REQUEST["type"] : 'post';

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
        if (isset($_REQUEST["only_published"]) && $_REQUEST["only_published"] == true) {
            $postStatus = array('publish');
        } elseif (isset($_REQUEST["only_trashed"]) && $_REQUEST["only_trashed"] == true) {
            $postStatus = array('trash');
        } else {
            $postStatus = array('draft', 'publish', 'pending');
        }
        if (isset($_REQUEST["inherit"]) && strlen($_REQUEST["inherit"]) > 0) {
            $postStatus[] = "inherit";
        }
        $args = [
            'post_type' => $type,
            'post_status' => $postStatus,
            'posts_per_page' => $count,
            'ignore_sticky_posts' => 1,
            'orderby' => $sort,
            'order' => $order,
            'paged' => $page,
        ];
        if (isset($_REQUEST["text"]) && strlen($_REQUEST["text"]) > 0) {
            $args["s"] = $_REQUEST["text"];
        }
        if (isset($_REQUEST["start_date"]) && strlen($_REQUEST["start_date"]) > 0
            && isset($_REQUEST["end_date"]) && strlen($_REQUEST["end_date"]) > 0) {
            $args['date_query'] = [
                [
                    'after' => $_REQUEST["start_date"],
                    'before' => $_REQUEST["end_date"],
                    'inclusive' => true,
                ]
            ];
        }
        if (isset($_REQUEST["meta_field"]) && strlen($_REQUEST["meta_field"]) > 0) {
            $meta_field = $_REQUEST["meta_field"];
            $meta_value = $_REQUEST["meta_text"];
            $meta_query = [
                [
                    'key' => $meta_field,
                    'value' => $meta_value,
                    'compare' => '=',
                ]
            ];
            $args["meta_query"] = $meta_query;
        } else {
            $meta_query = [];
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
            list($permalink, $postname) = get_sample_permalink($result->post->ID);
            $url = str_replace('%postname%', $postname, $permalink);

            $postData = new ShortPostDataResponse(
                postId: $result->post->ID,
                url: $url,
                title: $result->post->post_title,
            );

            $postsArray[] = $postData->toArray();
        }

        if (count($postsArray) === 0) {
            return new PostsListResponse([], false);
        }

        return new PostsListResponse(
            posts: $postsArray,
            hasMore: $hasMore,
        );
    }
}