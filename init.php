<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('vendor/autoload.php');

session_start();

use App\Repository\BlogpostRepository;
use App\Repository\CategorieRepository;
use App\Repository\CommentaireRepository;
use App\Repository\UserRepository;
use App\Repository\PhotoRepository;


const DB_SERVER = 'mariadb';
const DB_USERNAME = 'root';
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
    exit('Error connecting to database'); //Should be a message a typical user could understand
}

// filp whoops 
// todo service debug sécurité notification render base_de_donnée variable_environnement
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

$userRepository = new UserRepository($pdo);


$user = null;
$user_connecte = false;
if (isset($_SESSION, $_SESSION['user'])) {
    $user_connecte = true;
    $user = unserialize($_SESSION['user']);
}

$blogpostRepository = new BlogpostRepository($pdo);
$categorieRepository = new CategorieRepository($pdo);
$photoRepository = new PhotoRepository($pdo);
$commentaireRepository = new CommentaireRepository($pdo);
$categories = $categorieRepository->returnAllcategorie();

//todo service render
function render(String $template, array $parametres = []): void
{
    $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
    $twig = new \Twig\Environment($loader, [
        'cache' => false,
        'debug' => true
    ]);

    global $user, $user_connecte, $categories;
    $defaultParam = [
        'user_connecte' => $user_connecte,
        'user' => $user,
        'categories' => $categories,
    ];
    if (isset($_SESSION['notification'])) {
        $defaultParam['notification'] = $_SESSION['notification'];
    }
    echo $twig->render($template,  array_merge($defaultParam, $parametres));
    unset($_SESSION['notification']);
}
