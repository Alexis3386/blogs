<?php

namespace App\Controller;

class ComentController extends BaseController
{
    public function removeComent($comentId): void
    {
        $this->_manager["App\Model\Manager\ComentManager"]->deleteComment($comentId);
        $_SESSION['notification']['notice'] = 'Votre commentaire a bien été éffacé';
        $referer = explode('/', $_SERVER['HTTP_REFERER']);
        $referer = $referer[count($referer) - 1];
        header('location: /' . $referer);
    }
}
