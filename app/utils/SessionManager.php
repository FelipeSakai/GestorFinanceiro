<?php

namespace App\Utils;

class SessionManager
{
    public static function generateToken($userId)
    {
        $token = bin2hex(random_bytes(16));
        $_SESSION['user_tokens'][$token] = $userId;
        return $token;
    }

    public static function validateToken($token)
    {
        return $_SESSION['user_tokens'][$token] ?? null;
    }

    public static function destroyToken($token)
    {
        unset($_SESSION['user_tokens'][$token]);
    }
}
?>