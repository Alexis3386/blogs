<?php
require_once('init.php');

use App\Util;

if (
    !(Util::validStringNotEmpty($_POST, 'email')
        && Util::validStringNotEmpty($_POST, 'password')
    )
) {
    $_SESSION['notification'] = 'Merci de remplir les champs obligatoires';
    header('Location: /?p=connexion');
    exit();
}


$user = $userRepository->findUserConnected($_POST['password'], $_POST['email']);
if ($user === null) {
    $_SESSION['notification'] = 'Votre mot de passe ou votre email ne sont pas correctes';
    header('Location: /?p=connexion');
    exit();
}

$_SESSION['user'] = serialize($user);

header('Location: /');
