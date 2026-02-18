<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Actions;

use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\AbstractResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\BadRequestResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\PermissionDeniedResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\SuccessResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\UnprocessableEntityErrorResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\WrappedAttachmentDataResponse;

class UpdateSitemapAction extends AbstractAction
{
    public function getName(): string
    {
        return 'update_sitemap';
    }

    public function handle(int $userId): AbstractResponse
    {
        if (!isset($_REQUEST["sitemap_data"])) {
            return new BadRequestResponse();
        }
        if (!user_can($userId, 'edit_posts')) {
            return new PermissionDeniedResponse();
        }

        $sitemapData = base64_decode($_REQUEST["sitemap_data"]);

        $sitemapFile = ABSPATH . '/sitemap.xml';

        file_put_contents($sitemapFile, $sitemapData);

        return new SuccessResponse();
    }

    private function resolveUniqueFileName(string $imagePath, string $imageName): string
    {
        if (false === $this->imageExists($imagePath, $imageName)) {
            return $imagePath . '/' . $imageName;
        }

        $nameParts = pathinfo($imageName);
        $name = $nameParts['filename']; // "document"
        $extension = $nameParts['extension']; // "pdf"

        for ($i = 0; $i < 1000; $i++) {
            $newName = $name . '-' . $i . '.' . $extension;
            if (false === $this->imageExists($imagePath, $newName)) {
                return $imagePath . '/' . $newName;
            }
        }

        throw new \RuntimeException('Could not resolve unique file name');
    }

    private function imageExists(string $imagePath, string $imageName): bool
    {
        $attachmentFile = $imagePath . "/" . $imageName;

        return file_exists($attachmentFile);
    }
}