<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Responses;

class PostDataResponse extends AbstractResponse
{
    public function __construct(
        private readonly int     $postId,
        private readonly ?string $postTitle,
        private readonly ?string $postContent,
        private readonly ?string $postStatus,
        private readonly ?string $postType,
        private readonly ?string $postMimeType,
        private readonly ?int    $postParent,
        private readonly ?string $publishDate,
        private readonly ?string $lastModifiedDate,
        private readonly string  $postUrl,
        private readonly string  $categoryName,
        private readonly array   $tags,
        private readonly string  $author,
    ) {}

    public function toArray(): array
    {
        return [
            'data' => [
                'post_id' => $this->postId,
                'post_title' => $this->postTitle,
                'post_content' => $this->postContent,
                'post_status' => $this->postStatus,
                'post_parent' => $this->postParent,
                'post_type' => $this->postType,
                'post_mime_type' => $this->postMimeType,
                'publish_date' => $this->publishDate,
                'last_modified' => $this->lastModifiedDate,
                'post_url' => $this->postUrl,
                'category' => $this->categoryName,
                'tags' => $this->tags,
                'author' => $this->author,
            ]
        ];
    }
}