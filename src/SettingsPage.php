<?php

namespace WPAjaxConnector\WPAjaxConnectorPlugin;

class SettingsPage
{
    public const ADD_KEY_ACTION_NAME = 'wpajaxconnector_add_key';
    public const DELETE_KEY_ACTION_NAME = 'wpajaxconnector_delete_key';

    const SUCCESS_MESSAGE_KEY = 'wpajaxconnector_success';
    const FAIL_MESSAGE_KEY = 'wpajaxconnetor_fail';

    public function __construct(
        private Authenticator $authenticator
    ) {}

    public function registerOptionsPage(): void
    {
        add_action('admin_menu', function () {
            add_options_page(
                'WPAjaxConnector Settings',
                'WPAjaxConnector Settings',
                'manage_options',
                'WPAjaxConnector',
                function () {
                    $users = $this->authenticator->listUsersWithKeys();

                    require_once(__DIR__ . '/../templates/settings-page.php');
                }
            );
        });

        add_action('admin_post_' . self::ADD_KEY_ACTION_NAME, function () {
            if (!current_user_can('manage_options')) {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }

            $userId = intval($_REQUEST["user"]);

            $key = $this->authenticator->createKeyForUser($userId);

            $location = $_SERVER['HTTP_REFERER'];
            wp_safe_redirect(add_query_arg([
                self::SUCCESS_MESSAGE_KEY => "Key added successfully, save it securely because it will not be shown again: $key"
            ], $location));
        });

        add_action('admin_post_' . self::DELETE_KEY_ACTION_NAME, function () {
            $userId = intval($_REQUEST["user"]);

            $this->authenticator->deleteKeyForUser($userId);

            $location = $_SERVER['HTTP_REFERER'];

            wp_safe_redirect(add_query_arg([
                self::SUCCESS_MESSAGE_KEY => "Key deleted successfully"
            ], $location));
        });

        $this->renderNotifications();
    }

    public function renderNotifications(): void
    {
        add_action('admin_notices', function () {
            if (isset($_REQUEST[self::SUCCESS_MESSAGE_KEY])) {
                ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php echo filter_var($_REQUEST[self::SUCCESS_MESSAGE_KEY], FILTER_SANITIZE_SPECIAL_CHARS) ?></p>
                </div>
                <?php
            } elseif (isset($_REQUEST[self::FAIL_MESSAGE_KEY])) {
                ?>
                <div class="notice notice-error is-dismissible">
                    <p><?php echo filter_var($_REQUEST[self::FAIL_MESSAGE_KEY], FILTER_SANITIZE_SPECIAL_CHARS) ?></p>
                </div>
                <?php
            }
        });
    }
}