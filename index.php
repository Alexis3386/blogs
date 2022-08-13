<?php
require 'vendor/autoload.php';

// Routing
$page = 'home';
if (isset($_GET['p'])) {
    $page = $_GET['p'];
}

// Rendu du template
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
$twig = new \Twig\Environment($loader, [
    'cache' => false  // __DIR__ . '/tmp'
]);

switch ($page) {
    case 'inscription':
        echo $twig->render('inscription.twig');
        break;
    case 'home':
        echo $twig->render('home.twig');
        break;
    default:
        header('HTTp/1.0 404 Not Found');
        echo $twig->render('404.twig');
        break;
}