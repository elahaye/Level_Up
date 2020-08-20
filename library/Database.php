<?php

class Database
{
    /**
     * Return a connexion to the database
     * 
     * @return PDO
     */
    public static function connect(): PDO
    {
        $host = "localhost";
        $dbname = "level_up";
        $user = "root";
        $password = "";

        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);

        return $pdo;
    }
}
