<?php
require_once 'vendor/autoload.php';

session_start();
session_regenerate_id();

const DB_SERVER = 'mysql';
const DB_USERNAME = 'blogs';
const DB_PASSWORD = 'secret';
const DB_NAME = 'blogs';
const DB_PORT = '3306';
const CHARSET = 'utf8mb4';

const NB_POSTS_HOME = 4;

try {
    $pdo = new PDO(
        'mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME . ';charset=' . CHARSET . ';port=' . DB_PORT,
        DB_USERNAME,
        DB_PASSWORD
    );
} catch (Exception $e) {
    error_log($e->getMessage());
    throw new Exception('Error connecting to database');
}

// filp whoops
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

$user = null;
$user_connecte = false;
if (isset($_SESSION, $_SESSION['user'])) {
    $user_connecte = true;
    $user = unserialize($_SESSION['user']);
}
