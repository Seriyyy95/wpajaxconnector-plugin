<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\MSLS;

class MslsApi extends \lloc\Msls\MslsMain
{
    private array $languageSettings = [];

    public function updateLanguage(int $postId, array $languageSettings): void
    {
        $this->languageSettings = $languageSettings;
        $current_blog = $this->collection->get_current_blog();
        if (!is_null($current_blog)) {
            $this->languageSettings[$current_blog->get_language()] = (int)$postId;
        }

        $this->save($postId, \lloc\Msls\MslsOptionsPost::class);
    }

    public function get_input_array($object_id): array
    {
        return $this->languageSettings;
    }
}