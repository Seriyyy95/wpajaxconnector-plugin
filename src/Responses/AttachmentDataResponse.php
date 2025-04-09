<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Responses;

class AttachmentDataResponse extends AbstractResponse
{
    public function __construct(
        private readonly int     $attachmentId,
        private readonly ?string $attachmentUrl,
        private readonly ?int    $size,
        private readonly ?int    $width,
        private readonly ?int    $height,
        private readonly ?string $largeUrl,
        private readonly ?string $thumbnailUrl,
    ) {}

    public static function fromAttachmentId(int $attachmentId): self
    {
        $meta = wp_get_attachment_metadata($attachmentId);

        $attachmentUrl = wp_get_attachment_url($attachmentId);
        $thumbnailUrl = wp_get_attachment_image_url($attachmentId, 'thumbnail');
        $largeUrl = wp_get_attachment_image_url($attachmentId, 'large');
        if ($thumbnailUrl === false) {
            $thumbnailUrl = null;
        }
        if ($largeUrl === false) {
            $largeUrl = null;
        }

        return new static(
            attachmentId: $attachmentId,
            attachmentUrl: $attachmentUrl,
            size: $meta['filesize'],
            width: $meta['width'],
            height: $meta['height'],
            largeUrl: $largeUrl,
            thumbnailUrl: $thumbnailUrl,
        );
    }

    public function toArray(): array
    {
        return [
            "attachment_id" => $this->attachmentId,
            "attachment_url" => $this->attachmentUrl,
            'filesize' => $this->size,
            'width' => $this->width,
            'height' => $this->height,
            "sizes" => [
                'large' => $this->largeUrl,
                'thumbnail' => $this->thumbnailUrl,
            ],
        ];
    }
}