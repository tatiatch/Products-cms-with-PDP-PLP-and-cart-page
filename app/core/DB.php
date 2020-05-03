<?php

namespace app\core;

use R;

class DB
{
    private const DB_HOST = 'localhost';
    private const DB_NAME = 'Clipart';
    private const DB_USER = 'root';
    private const DB_PASSWORD = 'option123';

    /**
     * Establish MySql database connection
     */
    public static function connect() {
        R::setup(
            sprintf(
                'mysql:host=%s;dbname=%s',
                self::DB_HOST,
                self::DB_NAME
            ),
            self::DB_USER,
            self::DB_PASSWORD
        );
    }
}