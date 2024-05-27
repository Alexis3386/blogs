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
        if ($this->_manager["App\Model\Manager\UserManager"]->isMailUnique($email) &&
            $this->_manager["App\Model\Manager\UserManager"]->isPseudoUnique($pseudo)) {
            if ($this->_manager["App\Model\Manager\UserManager"]->enregistrer($password, $pseudo, $name, $email)) {
                $_SESSION['notification']['notice'] = 'Utilisateur enregistré';
                header('location: /');
            };
        }
    }

    public function ConnectShow(): void
    {
        $this->view('connexion.twig');
    }

    public function Connect($password, $email): void
    {
        $isValid = true;
        $user = $this->_manager["App\Model\Manager\UserManager"]->findUserConnected($email, $password);
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

    public function Logout(): void
    {
        session_destroy();
        $user_connecte = false;
        header('Location: /');
    }
}