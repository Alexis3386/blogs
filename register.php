<?php
require_once('init.php');

use App\Util;

if (isset($_POST) && !empty($_POST)) {

    $isValid = true;

    if (
        !(Util::validStringNotEmpty($_POST, 'pseudo')
            && Util::validStringNotEmpty($_POST, 'email')
            && Util::validStringNotEmpty($_POST, 'password')
            && Util::validStringNotEmpty($_POST, 'name'))
    ) {
        $_SESSION['notification']['warning'] = 'Merci de remplir les champs obligatoires';
        $isValid = false;
    } else {

        if (!$userRepository->isMailUnique($_POST['email'])) {
            $_SESSION['notification']['warning'] = 'Adresse email déja utilisé, merci d\'en choisir une autre';
            $isValid = false;
        }

        if (Util::checkMdpFormat($_POST['password'])) {
            $_SESSION['notification']['warning'] = 'Le mot de passe doit contenir 8 charactères, au moins 1 majuscule, 1 minuscule et 1 chiffre';
            $isValid = false;
        }

        if (!$userRepository->isPseudoUnique($_POST['pseudo'])) {
            $_SESSION['notification']['warning'] = 'Pseudo déja utilisé, merci d\'en choisir un autre';
            $isValid = false;
        }
    }

    if ($isValid) {
        $password = $_POST['password'];
        $email = $_POST['email'];
        $username = $_POST['name'];
        $pseudo = $_POST['pseudo'];

        if ($userRepository->enregistrer($password, $pseudo, $username, $email)) {
            $_SESSION['notification']['success'] = 'Votre compte a bien été créé vous pouvez vous connectez';
            header('Location: /connexion.php');
            exit();
        }
    }
}

render('inscription.twig');
