<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Actions;

use WPAjaxConnector\WPAjaxConnectorPlugin\MSLS\MslsApi;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\AbstractResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\BadRequestResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PostIdResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\SuccessResponse;

class SetTermTranslationAction extends AbstractAction
{
    public function getName(): string
    {
        return 'set_term_translation';
    }

    public function handle(int $userId): AbstractResponse
    {
        if (!isset($_REQUEST["term_id"])) {
            return new BadRequestResponse();
        }
        if (!isset($_REQUEST["locale"])) {
            return new BadRequestResponse();
        }
        if (!isset($_REQUEST["translation_id"])) {
            return new BadRequestResponse();
        }
        if (!isset($_REQUEST["type"])) {
            return new BadRequestResponse();
        }

        $taxonomyId = $_REQUEST["term_id"] ?? null;
        $translationId = $_REQUEST["translation_id"] ?? null;
        $locale = $_REQUEST["locale"] ?? null;
        $type = $_REQUEST["type"] ?? null;

        if ($locale === 'en') {
            $lang = 'en_US';
        } elseif ($locale === 'uk') {
            $lang = 'uk';
        } else {
            return new BadRequestResponse();
        }

        $options = msls_get_tax(intval($taxonomyId));

        $arr = $options->get_arr();
        $arr[$lang] = $translationId;

        $mslsApi = new MslsApi(
            msls_options(),
            msls_blog_collection()
        );

        if ($type === 'category') {
            $mslsApi->updateCategoryLanguage(intval($taxonomyId), $arr);
        } elseif ($type === 'post_tag') {
            $mslsApi->updateTagLanguage(intval($taxonomyId), $arr);
        } else {
            return new BadRequestResponse();
        }

        return new SuccessResponse();
    }
}