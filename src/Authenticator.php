<?php

declare(strict_types=1);

namespace WPAjaxConnector\WPAjaxConnectorPlugin;

class Authenticator
{
    const ACCESS_KEY_FIELD = 'wp_ajax_connector_key';

    public function authenticate(): int|false
    {
        if (!isset($_SERVER['HTTP_X_ACCESS_KEY'])) {
            return false;
        }

        $accessKey = sanitize_text_field($_SERVER['HTTP_X_ACCESS_KEY']);
        if (strlen($accessKey) == 0) {
            return false;
        }
        $encryptedKey = md5($accessKey);

        $userIds = $this->getUsers([
            'meta_key' => self::ACCESS_KEY_FIELD,
            'meta_value' => $encryptedKey,
            'fields' => 'ID',
        ]);

        if (count($userIds) > 0) {
            $userId = $userIds[0];

            wp_set_current_user($userId);

            return intval($userId);
        } else {
            return false;
        }
    }

    public function listUsersWithKeys(): array
    {
        //Array of std objects with fields
        $users = $this->getUsers([
            'meta_key' => self::ACCESS_KEY_FIELD,
            'meta_value' => '',
            'meta_compare' => '>',
            'fields' => ['ID', 'user_login'],
        ]);

        return $users;
    }

    public function createKeyForUser(int $userId): string
    {
        $key = $this->generateAccessKey();

        delete_user_meta($userId, self::ACCESS_KEY_FIELD);
        add_user_meta($userId, self::ACCESS_KEY_FIELD, md5($key), true);

        return $key;
    }

    public function deleteKeyForUser(int $userId): void
    {
        delete_user_meta($userId, self::ACCESS_KEY_FIELD);
    }

    private function generateAccessKey(): string
    {
        $length = 60;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    private function getUsers(array $args): array
    {
        //one users table across all sites in network
        return get_users($args);
    }
}