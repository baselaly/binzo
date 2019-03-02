<?php
session_start();

class CSRF
{

    public static function generate_token()
    {
        $_SESSION['key'] = bin2hex(random_bytes(32));
    }

    public static function exists()
    {
        return empty($_SESSION['key']) ? false : true;
    }

    public static function check_csrf_token($client_token)
    {
        if (!self::exists()) {
            self::generate_token();
        }
        $csrf = hash_hmac('sha256', 'this is some random text.', $_SESSION['key']);

        if (!hash_equals($csrf, $client_token)) {
            throw new \Exception('invalid csrf_token');
        }
    }

    public static function get_csrf_token()
    {
        if (!self::exists()) {
            self::generate_token();
        }
        $csrf = hash_hmac('sha256', 'this is some random text.', $_SESSION['key']);
        return $csrf;
    }
}
