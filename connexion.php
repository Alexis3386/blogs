<?php
require_once('init.php');

use App\Util;

if (isset($_POST) && !empty($_POST)) {
    $isValid = true;
    if (
        !(Util::validStringNotEmpty($_POST, 'email')
            && Util::validStringNotEmpty($_POST, 'password')
        )
    ) {
        $_SESSION['notification']['warning'] = 'Merci de remplir les champs obligatoires';
        $isValid = false;
    }

    $user = $userRepository->findUserConnected($_POST['password'], $_POST['email']);
    if ($user === null) {
        $_SESSION['notification']['warning'] = 'Votre mot de passe ou votre email ne sont pas correctes';
        $isValid = false;
    }

    if ($isValid) {
        $_SESSION['user'] = serialize($user);
        header('Location: /');
    }
}

render('connexion.twig', ['categories' => '']);
