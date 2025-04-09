<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Actions;

use WPAjaxConnector\WPAjaxConnectorPlugin\MSLS\MslsApi;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\AbstractResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\BadRequestResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PostIdResponse;

class SetPostTranslation extends AbstractAction
{
    public function getName(): string
    {
        return 'set_post_translation';
    }

    public function handle(int $userId): AbstractResponse
    {
        if (!isset($_REQUEST["post_id"])) {
            return new BadRequestResponse();
        }
        if (!isset($_REQUEST["locale"])) {
            return new BadRequestResponse();
        }
        if (!isset($_REQUEST["translation_id"])) {
            return new BadRequestResponse();
        }

        $postId = $_REQUEST["post_id"] ?? null;
        $translationId = $_REQUEST["translation_id"] ?? null;
        $locale = $_REQUEST["locale"] ?? null;

        if ($locale === 'en') {
            $lang = 'en_US';
        } elseif ($locale === 'uk') {
            $lang = 'uk';
        } else {
            return new BadRequestResponse();
        }

        $options = msls_get_post(intval($postId));

        $arr = $options->get_arr();
        $arr[$lang] = $translationId;

        $mslsApi = new MslsApi(
            msls_options(),
            msls_blog_collection()
        );

        $mslsApi->updateLanguage(intval($postId), $arr);

        return new PostIdResponse(intval($postId));
    }
}