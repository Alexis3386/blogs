<?php
require_once('config.php');
require_once('vendor/autoload.php');

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

$req_mail = $pdo->prepare("SELECT email FROM users WHERE email = :email");
$req_mail->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
$req_mail->execute();
$resultat = $req_mail->fetch(PDO::FETCH_ASSOC);
if ($resultat) {
    header('Location: /?p=inscription&mail_dejautilise');
    exit();
}

if (!Util::checkMdpFormat($_POST['password'])) {
    header('Location: /?p=inscription&mdp_invalide');
    exit();
}

$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$query = $pdo->prepare("INSERT INTO `users` (pseudo, username, email, password) VALUES (:pseudo, :username, :email, :password)");
$query->bindParam(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
$query->bindParam(':username', $_POST['name'], PDO::PARAM_STR);
$query->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
$query->bindParam(':password', $password, PDO::PARAM_STR);
$inscription = $query->execute();

if ($inscription) {
    header('Location: /?p=inscription&succes');
    exit();
}
