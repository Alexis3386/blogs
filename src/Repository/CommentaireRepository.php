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

    public function findByPost(int $idPost): array {
        $query = $this->pdo->prepare('SELECT * FROM `commentaires` WHERE idPost = :idPost');
        $query->bindParam(':idPost', $idPost, PDO::PARAM_STR);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);

    }

    public function findCommentPending(): array {
        $query = $this->pdo->prepare('SELECT * FROM `commentaires` WHERE isValide = 0');
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findCommentByPost(int $idPost, bool $isValide): array {
        $query = $this->pdo->prepare('SELECT * FROM `commentaires` WHERE isValide = :isValide and idPost = :idPost');
        $query->bindParam(':idPost', $idPost, PDO::PARAM_INT);
        $query->bindParam(':isValide', $isValide, PDO::PARAM_BOOL);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteComment(int $id): bool {
        $query = $this->pdo->prepare('DELETE FROM `commentaires` WHERE idComment = :id');
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        return $query->execute();
    }

    public function commentValidate(int $id): bool {
        $query = $this->pdo->prepare('UPDATE `commentaires` SET isValide = true WHERE idComment = :id');
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        return $query->execute();
    }
}