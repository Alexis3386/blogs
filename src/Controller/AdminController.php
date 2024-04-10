<?php

namespace App\Controller;

class AdminController extends BaseController
{
    public function index(): void
    {
        $user = $this->getCurrentUser();

        if ($user === null || !$user->getisadmin()) {
            $this->view("administration/administrationSignin.twig");
            return;
        }

        $commentsPending = $this->_manager["App\Model\Manager\ComentManager"]->findCommentPending();
        $this->view('administration/adminComments.twig', ['commentsPending' => $commentsPending]);
    }

    public function validComent($commentToValidate): void
    {
        $this->_manager["App\Model\Manager\ComentManager"]->comentValidate($commentToValidate);
        $_SESSION['notification']['notice'] = 'Le commentaire a été validé';
        header('location: /administration');
    }
}