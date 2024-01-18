<?php

namespace Core\Database;

use Core\Database\ConnexionDatas;
use PDO;


class Database extends ConnexionDatas
{
    private static $connection;

    private function __construct()
    {
        // Empêche l'instanciation directe de la classe.
    }

    public static function connect()
    {
        if (self::$connection === null) {
            $host = parent::HOST;    // Mettez votre hôte de base de données ici.
            $dbname = parent::DBNAME;  // Mettez le nom de votre base de données ici.
            $username = parent::USERNAME;  // Mettez votre nom d'utilisateur de base de données ici.
            $password = parent::PASSWORD;  // Mettez votre mot de passe de base de données ici.
            
            self::$connection = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        }
        return self::$connection;
    }
}
