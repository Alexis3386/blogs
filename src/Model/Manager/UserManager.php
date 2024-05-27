<?php

namespace App\Model\Manager;

use App\Framework\BaseManager;
use App\Model\Entity\Blogpost;
use App\Model\Entity\User;
use PDO;

class UserManager extends BaseManager
{
    public function __construct($datasource)
    {
        parent::__construct("users", "User", $datasource);
    }

    public function findUserConnected(String $password, String $email): ?User
    {
        $query = $this->_bdd->prepare("SELECT * FROM `users` WHERE email = :email");
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();
        $user = $query->fetch(PDO::FETCH_ASSOC);
        if (!empty($user)) {
            $hash = $user['password'];
            if (password_verify($password, $hash)) {
                return new User($user['id'], $user['email'], $user['pseudo'], $user['username'], $user['isadmin']);
            }
        }
        return null;
    }

    public function findAuthor(Blogpost $post): ?User
    {
        $id = $post->getAuthorId();
        $query = $this->_bdd->prepare('SELECT * FROM `users` WHERE id = :id');
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $user = $query->fetch(PDO::FETCH_ASSOC);
        if (!empty($user)) {
            return new User($user['id'], $user['email'], $user['pseudo'], $user['username'], $user['isadmin']);
        }
        return null;
    }

    public function isMailUnique(String $mail): bool
    {
        $verif_mail = $this->_bdd->prepare("SELECT email FROM users WHERE email = :email");
        $verif_mail->bindParam(':email', $mail, PDO::PARAM_STR);
        $verif_mail->execute();
        return empty($verif_mail->fetch(PDO::FETCH_ASSOC));
    }

    public function isPseudoUnique(String $pseudo): bool
    {
        $ver_pseudo = $this->_bdd->prepare("SELECT pseudo FROM users WHERE pseudo = :pseudo");
        $ver_pseudo->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
        $ver_pseudo->execute();
        return empty($ver_pseudo->fetch(PDO::FETCH_ASSOC));
    }

    public function enregistrer(String $password, String $pseudo, String $username, String $email, bool $isAdmin = false): bool
    {
        $password = password_hash($password, PASSWORD_BCRYPT);
        $query = $this->_bdd->prepare("INSERT INTO `users` (pseudo, username, email, password, isadmin) VALUES (:pseudo, :username, :email, :password, :isadmin)");
        $query->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->bindParam(':isadmin', $isAdmin, PDO::PARAM_INT);
        return $query->execute();
    }
}