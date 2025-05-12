<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Utils;

class AttachmentFinder
{
    public static function findAttachmentsByUrl($url): array
    {
        global $wpdb;

        $dir = wp_get_upload_dir();
        $path = $url;

        $site_url = parse_url($dir['url']);
        $image_path = parse_url($path);

        // Force the protocols to match if needed.
        if (isset($image_path['scheme']) && ($image_path['scheme'] !== $site_url['scheme'])) {
            $path = str_replace($image_path['scheme'], $site_url['scheme'], $path);
        }

        if (str_starts_with($path, $dir['baseurl'] . '/')) {
            $path = substr($path, strlen($dir['baseurl'] . '/'));
        }

        $sql = $wpdb->prepare(
            "SELECT post_id, meta_value FROM $wpdb->postmeta WHERE meta_key = '_wp_attached_file' AND meta_value = %s",
            $path
        );

        $results = $wpdb->get_results($sql);
        $postIds = [];

        if ($results) {
            foreach ($results as $result) {
                if ($path === $result->meta_value) {
                    $postIds[] = $result->post_id;
                }
            }
        }

        return $postIds;
    }
}