<?php
require 'vendor/autoload.php';

session_start();

$mail_dejautilise = false;
$inscription_sucess = false;
$mdp_invalide = false;
$champ_vide = false;
if (isset($_GET['mail_dejautilise'])) {
    $mail_dejautilise = true;
}

if (isset($_GET['succes'])) {
    $inscription_sucess = true;
}

if (isset($_GET['mdp_invalide'])) {
    $mdp_invalide = true;
}

if (isset($_GET['champ_vide'])) {
    $champ_vide = true;
}

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
        echo $twig->render('inscription.twig',
            ['maildejautilise' => $mail_dejautilise,
                'inscriptionsucces' => $inscription_sucess,
                'mdpinvalide' => $mdp_invalide,
                'champvide' => $champ_vide,
            ]);
        break;
    case 'connexion' :
        echo $twig->render('connexion.twig');
        break;
    case 'home':
        echo $twig->render('home.twig');
        break;
    default:
        header('HTTp/1.0 404 Not Found');
        echo $twig->render('404.twig');
        break;
}