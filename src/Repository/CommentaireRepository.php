<?php

namespace App\Repository;

use App\Entity\Commentaire;
use PDO;
use DateTime;

class CommentaireRepository {

    public function __construct(private PDO $pdo)
    {
    }

    public function enregistrer(Commentaire $commentaire): bool {

        $commentaire->setDateCreation(new DateTime());
        $contenu = $commentaire->getContent();
        $dateCreation = $commentaire->getDateCreation()->format("Y-m-d H:i:s");
        $idUser = $commentaire->getIdUser();
        $idPost = $commentaire->getIdPost();

        $query = $this->pdo->prepare('INSERT INTO `commentaires` (contenu, datePublication, idUser, idPost) 
                                        VALUE (:contenu, :datePublication, :idUser, :idPost)');
        $query->bindParam(':contenu', $contenu, PDO::PARAM_STR);
        $query->bindParam(':datePublication', $dateCreation, PDO::PARAM_STR);
        $query->bindParam(':idUser', $idUser, PDO::PARAM_INT);
        $query->bindParam(':idPost', $idPost, PDO::PARAM_INT);
        return $query->execute();
    }
}