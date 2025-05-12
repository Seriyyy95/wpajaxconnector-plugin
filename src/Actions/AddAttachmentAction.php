<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Actions;

use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\AbstractResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\BadRequestResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PermissionDeniedResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\UnprocessableEntityErrorResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\WrappedAttachmentDataResponse;

class AddAttachmentAction extends AbstractAction
{
    public function getName(): string
    {
        return 'add_attachment';
    }

    public function handle(int $userId): AbstractResponse
    {
        if (!isset($_REQUEST["attachment_name"]) || !isset($_REQUEST["attachment_data"])) {
            return new BadRequestResponse();
        }
        if (!user_can($userId, 'edit_posts')) {
            return new PermissionDeniedResponse();
        }

        $post_id = $_REQUEST["post_id"] ?? null;

        if (null !== $post_id) {
            $date = get_the_date('Y/m', $post_id);
        } else {
            $date = null;
        }

        $imageData = base64_decode($_REQUEST["attachment_data"]);
        $imageName = $_REQUEST["attachment_name"];
        $uploadDirData = wp_upload_dir($date);
        $imagePath = $uploadDirData["path"];
        $attachmentFile = $imagePath . "/" . $imageName;

        if(file_exists($attachmentFile)){
            return new UnprocessableEntityErrorResponse("File for this attachment already exists");
        }

        file_put_contents($attachmentFile, $imageData);

        $url = $uploadDirData['url'] . "/$imageName";

        $wp_filetype = wp_check_filetype($attachmentFile, null);
        $attachment = [
            'guid' => $url,
            'post_mime_type' => $wp_filetype['type'],
            'post_title' => basename($imageName),
            'post_author' => $userId,
            'post_content' => '',
            'post_status' => 'inherit',
        ];
        $attach_id = wp_insert_attachment($attachment, $attachmentFile, $post_id);

        //Fix empty thumbnail url when W3TC DBCache enabled
        if (function_exists('w3tc_dbcache_flush')) {
            w3tc_dbcache_flush();
        }

        require_once ABSPATH . 'wp-admin/includes/image.php';
        $attach_data = wp_generate_attachment_metadata($attach_id, $attachmentFile);

        wp_update_attachment_metadata($attach_id, $attach_data);

        return WrappedAttachmentDataResponse::fromAttachmentId(intval($attach_id));
    }
}