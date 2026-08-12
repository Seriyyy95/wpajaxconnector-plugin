<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin;

use WPAjaxConnector\WPAjaxConnectorPlugin\Actions\AddAttachmentAction;
use WPAjaxConnector\WPAjaxConnectorPlugin\Actions\AddCategoryAction;
use WPAjaxConnector\WPAjaxConnectorPlugin\Actions\AddPostAction;
use WPAjaxConnector\WPAjaxConnectorPlugin\Actions\AddTagAction;
use WPAjaxConnector\WPAjaxConnectorPlugin\Actions\DeleteAttachmentAction;
use WPAjaxConnector\WPAjaxConnectorPlugin\Actions\DeletePostAction;
use WPAjaxConnector\WPAjaxConnectorPlugin\Actions\GetAttachmentAction;
use WPAjaxConnector\WPAjaxConnectorPlugin\Actions\GetPostBlocksAction;
use WPAjaxConnector\WPAjaxConnectorPlugin\Actions\GetPostDataAction;
use WPAjaxConnector\WPAjaxConnectorPlugin\Actions\GetPostKeywords;
use WPAjaxConnector\WPAjaxConnectorPlugin\Actions\GetPostMetaAction;
use WPAjaxConnector\WPAjaxConnectorPlugin\Actions\GetPostThumbnailAction;
use WPAjaxConnector\WPAjaxConnectorPlugin\Actions\GetPostTranslationsAction;
use WPAjaxConnector\WPAjaxConnectorPlugin\Actions\GetTermDataAction;
use WPAjaxConnector\WPAjaxConnectorPlugin\Actions\IsAccessibleAction;
use WPAjaxConnector\WPAjaxConnectorPlugin\Actions\ListAttachmentsAction;
use WPAjaxConnector\WPAjaxConnectorPlugin\Actions\ListPostsAction;
use WPAjaxConnector\WPAjaxConnectorPlugin\Actions\SetPostAuthorAction;
use WPAjaxConnector\WPAjaxConnectorPlugin\Actions\SetPostCategoryAction;
use WPAjaxConnector\WPAjaxConnectorPlugin\Actions\SetPostContentAction;
use WPAjaxConnector\WPAjaxConnectorPlugin\Actions\SetPostMetaAction;
use WPAjaxConnector\WPAjaxConnectorPlugin\Actions\SetPostParentAction;
use WPAjaxConnector\WPAjaxConnectorPlugin\Actions\SetPostStatusAction;
use WPAjaxConnector\WPAjaxConnectorPlugin\Actions\SetPostTagsAction;
use WPAjaxConnector\WPAjaxConnectorPlugin\Actions\SetPostTranslationAction;
use WPAjaxConnector\WPAjaxConnectorPlugin\Actions\SetPostThumbnailAction;
use WPAjaxConnector\WPAjaxConnectorPlugin\Actions\SetPostTitleAction;
use WPAjaxConnector\WPAjaxConnectorPlugin\Actions\SetTermDescriptionAction;
use WPAjaxConnector\WPAjaxConnectorPlugin\Actions\SetTermNameAction;
use WPAjaxConnector\WPAjaxConnectorPlugin\Actions\SetTermSlugAction;
use WPAjaxConnector\WPAjaxConnectorPlugin\Actions\UpdateAttachmentAction;
use WPAjaxConnector\WPAjaxConnectorPlugin\Actions\SetPostBlocksAction;
use WPAjaxConnector\WPAjaxConnectorPlugin\Actions\UpdateSitemapAction;
use WPAjaxConnector\WPAjaxConnectorPlugin\Actions\SetTermTranslationAction;

class Bootstrap
{
    private Authenticator $authenticator;
    private SettingsPage $settingsPage;

    private array $actions = [
        ListPostsAction::class,
        ListAttachmentsAction::class,
        IsAccessibleAction::class,
        GetPostDataAction::class,
        GetPostMetaAction::class,
        GetPostBlocksAction::class,
        GetPostKeywords::class,
        GetTermDataAction::class,
        SetPostBlocksAction::class,
        SetPostTranslationAction::class,
        AddAttachmentAction::class,
        AddCategoryAction::class,
        AddTagAction::class,
        UpdateAttachmentAction::class,
        DeleteAttachmentAction::class,
        GetAttachmentAction::class,
        AddPostAction::class,
        SetPostThumbnailAction::class,
        SetPostContentAction::class,
        SetPostParentAction::class,
        SetPostCategoryAction::class,
        SetPostTagsAction::class,
        SetPostTitleAction::class,
        SetPostMetaAction::class,
        SetTermNameAction::class,
        SetTermSlugAction::class,
        SetTermTranslationAction::class,
        SetTermDescriptionAction::class,
        SetPostAuthorAction::class,
        SetPostStatusAction::class,
        DeletePostAction::class,
        GetPostTranslationsAction::class,
        GetPostThumbnailAction::class,
        UpdateSitemapAction::class
    ];

    public function __construct()
    {
        $this->authenticator = new Authenticator();
        $this->settingsPage = new SettingsPage($this->authenticator);

        foreach ($this->actions as $actionClass) {
            $action = new $actionClass($this->authenticator);

            add_action('wp_ajax_' . $action->getName(), [$action, 'execute']);
            add_action('wp_ajax_nopriv_' . $action->getName(), [$action, 'execute']);
        }

        $this->settingsPage->registerOptionsPage();
    }
}