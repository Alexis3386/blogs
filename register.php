<?php
require_once('init.php');

use App\Util;


if (
!(Util::validStringNotEmpty($_POST, 'pseudo')
    && Util::validStringNotEmpty($_POST, 'email')
    && Util::validStringNotEmpty($_POST, 'password')
    && Util::validStringNotEmpty($_POST, 'name'))
) {
    header('Location: /?p=inscription&champ_vide');
    exit();
}

if (!$userRepository->isMailUnique($_POST['email'])) {
    header('Location: /?p=inscription&mail_dejautilise');
    exit();
}

if (Util::checkMdpFormat($_POST['password'])) {
    header('Location: /?p=inscription&mdp_invalide');
    exit();
}

if (!$userRepository->isPseudoUnique($_POST['pseudo'])) {
    header('Location: /?p=inscription&pseudo_dejautilise');
    exit();
}

$password = $_POST['password'];
$email = $_POST['email'];
$username = $_POST['name'];
$pseudo = $_POST['pseudo'];

if ($userRepository->enregistreUser($password, $pseudo, $username, $email)) {
    header('Location: /?p=inscription&succes');
    exit();
}
