<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Actions;

use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\AbstractResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\BadRequestResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PostTranslationsResponse;

class GetPostTranslationsAction extends AbstractAction
{
    public function getName(): string
    {
        return 'get_post_translations';
    }

    public function handle(int $userId): AbstractResponse
    {
        if (!isset($_REQUEST["post_id"])) {
            return new BadRequestResponse();
        }

        $postId = $_REQUEST["post_id"] ?? null;

        $options = msls_get_post($postId);

        $translations = [];
        foreach ($options->get_arr() as $lang => $postId) {
            $parts = explode('_', $lang);
            if (count($parts) !== 2) {
                $translations[$lang] = $postId;
            } else {
                $translations[$parts[0]] = $postId;
            }
        }

        return new PostTranslationsResponse($translations);
    }
}