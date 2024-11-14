<?php

namespace App\Models;

use PDO;

class Database {
    private static $instance = null;

    public static function getConnection() {
        if (!self::$instance) {
            self::$instance = new PDO("mysql:host=localhost;dbname=gestorfinanceiro", "root", "");
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
        return self::$instance;
    }
}

