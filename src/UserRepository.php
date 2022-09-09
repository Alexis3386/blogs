<?php


namespace App;

use PDO;
use App\User;

class UserRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function isMailUnique(String $mail): bool
    {
        $verif_mail = $this->pdo->prepare("SELECT email FROM users WHERE email = :email");
        $verif_mail->bindParam(':email', $mail, PDO::PARAM_STR);
        $verif_mail->execute();
        return empty($verif_mail->fetch(PDO::FETCH_ASSOC));
    }

    public function isPseudoUnique(String $pseudo): bool
    {
        $ver_pseudo = $this->pdo->prepare("SELECT pseudo FROM users WHERE pseudo = :pseudo");
        $ver_pseudo->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
        $ver_pseudo->execute();
        return empty($ver_pseudo->fetch(PDO::FETCH_ASSOC));
    }

    public function enregistreUser(String $password, String $pseudo, String $username, STring $email): bool {
        $password = password_hash($password, PASSWORD_BCRYPT);
        $query = $this->pdo->prepare("INSERT INTO `users` (pseudo, username, email, password) VALUES (:pseudo, :username, :email, :password)");
        $query->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        return $query->execute();
    }

    public function isIdentifiantValid(String $password, String $email): bool {
        $password = password_hash($password, PASSWORD_BCRYPT);
        $query = $this->pdo->prepare("SELECT * FROM `users` WHERE email = :email AND password = :password");
        $query->bindParam(':email', $email, PDO::PARAM_STR );
        $query->bindParam(':password', $password, PDO::PARAM_STR );
        $query->execute();
        return empty($query->fetch(PDO::FETCH_ASSOC));
    }

    public function connecteUser(String $email) : User {
        $sql = "SELECT * FROM users where email = :email";
        $query = $this->pdo->prepare($sql);
        $query->bindParam(":email", $email, PDO::PARAM_STR);
        $query->execute();
        $user = $query->fetch();
        return new User($user['id'], $user['email'], $user['pseudo'], $user['username'], $user['isadmin']);
    }
}
