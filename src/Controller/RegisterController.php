<?php

namespace App\Controller;

use App\Model\Entity\User;

class RegisterController extends BaseController
{
    public function RegisterShow(): void
    {
        $this->view("inscription.twig");
    }

    public function Register($email, $name, $pseudo, $password): void
    {
        $user = new User($email, $name, $pseudo, false, $password,);
        $this->_manager->create($user, ['email', 'username', 'pseudo', 'isadmin', 'password']);
    }

    public function ConnectShow(): void
    {
        $this->view('connexion.twig');
    }

    public function Connect($password, $email): void
    {
        $isValid = true;
        $user = $this->_manager->findUserConnected($email, $password);
        if ($user === null) {
            $_SESSION['notification']['warning'] = 'Votre mot de passe ou votre email ne sont pas correctes';
            $isValid = false;
            header('Location: /connexion');
        }

        if ($isValid) {
            $_SESSION['user'] = serialize($user);
            header('Location: /');
        }
    }

    public function Logout(): void {
        session_destroy();
        $user_connecte = false;
        header('Location: /');
    }
}