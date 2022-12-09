<?php
require_once 'vendor/autoload.php';
require_once 'src/utilimage.php';

session_start();

use App\Repository\BlogpostRepository;
use App\Repository\CategorieRepository;
use App\Repository\CommentaireRepository;
use App\Repository\UserRepository;
use App\Repository\PhotoRepository;


const DB_SERVER = 'localhost';
const DB_USERNAME = 'root';
const DB_PASSWORD = '';
const DB_NAME = 'blogs';
const DB_PORT = '3307';
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

$_POST = array_map('htmlspecialchars', $_POST);
