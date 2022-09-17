<?php
require_once('init.php');

use App\Util;

echo $twig->render('connexion.twig', [
    'session' => $_SESSION,
    'user_connecte' => $user_connecte,
    'user' => $user,
]);


if (isset($_POST)) {
    if (
        !(Util::validStringNotEmpty($_POST, 'email')
            && Util::validStringNotEmpty($_POST, 'password')
        )
    ) {
        $_SESSION['notification']['warning'] = 'Merci de remplir les champs obligatoires';
        exit();
    }

    $user = $userRepository->findUserConnected($_POST['password'], $_POST['email']);
    if ($user === null) {
        $_SESSION['notification']['warning'] = 'Votre mot de passe ou votre email ne sont pas correctes';
        exit();
    }

    $_SESSION['user'] = serialize($user);

    header('Location: /');
}
