<?php
require 'vendor/autoload.php';

session_start();
$user_connecte = false;
if (isset($_SESSION) && !empty($_SESSION)) {
    $user_connecte = true;
}

$mail_dejautilise = false;
$inscription_sucess = false;
$mdp_invalide = false;
$champ_vide = false;
$pseudo_dejautilise = false;

// déconnexion

if (isset($_GET['deconnexion'])) {
    $_SESSION = array();
    $user_connecte = false;
    header('Location: /');
    exit();
}


//test inscription
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

if (isset($_GET['pseudo_dejautilise'])) {
    $pseudo_dejautilise = true;
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
                'pseudodejautilise' => $pseudo_dejautilise,
                'user_connecte' => $user_connecte,
            ]);
        break;
    case 'connexion' :
        echo $twig->render('connexion.twig');
        break;
    case 'home':
        echo $twig->render('home.twig', [
            'user_connecte' => $user_connecte,
            'session' => $_SESSION],);
        break;
    default:
        header('HTTp/1.0 404 Not Found');
        echo $twig->render('404.twig');
        break;
}