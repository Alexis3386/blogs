<?php
/** Informations d'identification **/
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '861517');
define('DB_NAME', 'blogs');
define('DB_PORT', '3306');
define('CHARSET', 'utf8mb4');

try {
    $pdo = new PDO('mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME . ';charset=' . CHARSET . ';port=' . DB_PORT,
        DB_USERNAME, DB_PASSWORD);
} catch (Exception $e) {
    error_log($e->getMessage());
    exit('Error connecting to database'); //Should be a message a typical user could understand
}
