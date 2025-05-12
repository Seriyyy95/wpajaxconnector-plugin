<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Actions;

use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\AbstractResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\BadRequestResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PermissionDeniedResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\UnprocessableEntityErrorResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\WrappedAttachmentDataResponse;

class UpdateAttachmentAction extends AbstractAction
{
    public function getName(): string
    {
        return 'update_attachment';
    }

    public function handle(int $userId): AbstractResponse
    {
        if (!isset($_REQUEST["attachment_id"])) {
            return new BadRequestResponse();
        }

        if (!isset($_REQUEST["attachment_data"])) {
            return new BadRequestResponse();
        }

        if (!user_can($userId, 'edit_posts')) {
            return new PermissionDeniedResponse();
        }

        $attachmentId = $_POST['attachment_id'] ?? null;

        if (empty($attachmentId)) {
            return new UnprocessableEntityErrorResponse("Attachment ID can't be empty");
        }

        $postId = get_post($attachmentId)?->post_parent;
        if ($postId !== null) {
            $date = get_the_date('Y/m', $postId);
        } else {
            $date = null;
        }

        //Remove old image

        $meta = wp_get_attachment_metadata($attachmentId);
        $backup_sizes = get_post_meta($attachmentId, '_wp_attachment_backup_sizes', true);
        $file = get_attached_file($attachmentId);

        wp_delete_attachment_files($attachmentId, $meta, $backup_sizes, $file);

        //Save new image
        $imageData = base64_decode($_POST["attachment_data"]);
        $imageName = $_POST["attachment_name"];
        $uploadDirData = wp_upload_dir($date);
        $imagePath = $uploadDirData["path"];
        $attachmentFile = $imagePath . "/" . $imageName;
        file_put_contents($attachmentFile, $imageData);

        $url = $uploadDirData['url'] . "/$imageName";

        $wp_filetype = wp_check_filetype($attachmentFile, null);

        update_attached_file($attachmentId, $attachmentFile);

        require_once ABSPATH . 'wp-admin/includes/image.php';

        //Fix empty thumbnail url when W3TC DBCache enabled
        if (function_exists('w3tc_dbcache_flush')) {
            w3tc_dbcache_flush();
        }

        $attach_data = wp_generate_attachment_metadata($attachmentId, $attachmentFile);
        wp_update_attachment_metadata($attachmentId, $attach_data);

        return WrappedAttachmentDataResponse::fromAttachmentId(intval($attachmentId));
    }
}