<?php

namespace App\Model\Manager;

use App\Framework\BaseManager;
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
}