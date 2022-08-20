<?php
require('config.php');
require_once('vendor/autoload.php');

use App\Util;

if (
    !(Util::validStringNotEmpty($_POST, 'pseudo')
    && Util::validStringNotEmpty($_POST, 'email')
    && Util::validStringNotEmpty($_POST, 'password')
    && Util::validStringNotEmpty($_POST, 'name'))
) {
    $php_errormsg = 'Merci de remplir les champs obligatoire';
    exit($php_errormsg);
}

$req_mail = $pdo->prepare("SELECT mail FROM users WHERE mail = :email");
$req_mail = $req_mail->bindParam(':email', $_POST['email'], PDO::PARAM_STR);



$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$query = $pdo->prepare("INSERT INTO `users` (pseudo, username, email, password) VALUES (:pseudo, :username, :email, :password)");
$query->bindParam(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
$query->bindParam(':username', $_POST['name'], PDO::PARAM_STR);
$query->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
$query->bindParam(':password', $password, PDO::PARAM_STR);
$query->execute();
