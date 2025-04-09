<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Actions;

use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\AbstractResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\BadRequestResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\InternalServerErrorResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PermissionDeniedResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PostIdResponse;

class SetPostBlocksAction extends AbstractAction
{
    public function getName(): string
    {
        return 'set_post_blocks';
    }

    public function handle(int $userId): AbstractResponse
    {
        if (!isset($_REQUEST["post_id"])) {
            return new BadRequestResponse();
        }
        if (!isset($_REQUEST["blocks"])) {
            return new BadRequestResponse();
        }

        $post_id = $_REQUEST["post_id"];
        if (!user_can($userId, 'edit_post', $post_id)) {
            return new PermissionDeniedResponse($post_id);
        }

        $post_id = intval($_REQUEST['post_id']);

        $blocksString = stripslashes($_REQUEST['blocks']);
        try {
            $blocks = json_decode($blocksString, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return new InternalServerErrorResponse($e->getMessage());
        }

        $blockRegistry = \WP_Block_Type_Registry::get_instance();

        if (!is_array($blocks)) {
            return new InternalServerErrorResponse('Invalid blocks array provided.');
        }

        // Iterate over the blocks array and validate each block.
        foreach ($blocks as $block) {
            if (!is_array($block)
                || !array_key_exists('blockName', $block)
                || !isset($block['attrs'])
                || !isset($block['innerHTML'])) {

                return new InternalServerErrorResponse('Invalid block object found' . print_r($block, true));
            }

            if (false === $blockRegistry->is_registered($block['blockName']) && $block['blockName'] !== null) {
                return new InternalServerErrorResponse('Unknown block found: ' . $block['blockName']);
            }
        }

        $post = get_post($post_id);

        $postData = [
            'ID' => $post_id,
            'post_content' => serialize_blocks($blocks)
        ];

        wp_update_post($postData);

        return new PostIdResponse($post->ID);
    }
}