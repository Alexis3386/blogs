<?php


require_once('vendor/autoload.php');

session_start();

use App\UserRepository;

const DB_SERVER = 'localhost';
const DB_USERNAME = 'root';
const DB_PASSWORD = '861517';
const DB_NAME = 'blogs';
const DB_PORT = '3306';
const CHARSET = 'utf8mb4';


try {
    $pdo = new PDO(
        'mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME . ';charset=' . CHARSET . ';port=' . DB_PORT,
        DB_USERNAME,
        DB_PASSWORD
    );
} catch (Exception $e) {
    error_log($e->getMessage());
    exit('Error connecting to database'); //Should be a message a typical user could understand
}

$userRepository = new UserRepository($pdo);

$user = null;
$user_connecte = false;
if (isset($_SESSION, $_SESSION['user'])) {
    $user_connecte = true;
    $user = unserialize($_SESSION['user']);
}

function render(String $template, array $parametres = []) : void
{
    $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
    $twig = new \Twig\Environment($loader, [
        'cache' => false,
        'debug' => true,  // __DIR__ . '/tmp'
    ]);
    
    global $user, $user_connecte;
    $defaultParam = [
        'user_connecte' => $user_connecte,
        'user' => $user,
    ];
    if (isset($_SESSION['notification'])){
        $defaultParam['notification'] = $_SESSION['notification'];
    }
    echo $twig->render($template,  array_merge($defaultParam, $parametres));
    unset($_SESSION['notification']);
}
