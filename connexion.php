<?php
require_once('init.php');

use App\Util;

if (
!(Util::validStringNotEmpty($_POST, 'email')
    && Util::validStringNotEmpty($_POST, 'password')
)
) {
    header('Location: /?p=connexion&champ_vide');
    exit();
}


if (!$userRepository->isIdentifiantValid($_POST['password'], $_POST['email'])) {
    header('Location: /?p=connexion&erreur_identifiant');
    exit();
}

$email = $_POST['email'];
$user = $userRepository->connecteUser($email);

if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = serialize($user);
}

header('Location: /');