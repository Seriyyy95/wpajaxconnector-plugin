<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin\Actions;

use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\AbstractResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Responses\AccessDeniedResponse;
use WPAjaxConnector\WPAjaxConnectorPlugin\Authenticator;

abstract class AbstractAction
{
    public function __construct(
        private Authenticator $authenticator
    ) {}

    public function execute(): void
    {
        $userId = $this->authenticator->authenticate();
        header('content-type: application/json; charset=utf-8');

        if (false === $userId) {
            $response = new AccessDeniedResponse();
        } else {
            $response = $this->handle($userId);
        }

        status_header($response->getCode());

        echo json_encode($response->toArray(), JSON_UNESCAPED_UNICODE);

        die();
    }

    public abstract function getName(): string;

    protected abstract function handle(int $userId): AbstractResponse;
}