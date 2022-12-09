<?php

namespace App\Repository;

use App\Entity\Commentaire;
use PDO;
use DateTime;

class CommentaireRepository
{

    public function __construct(private PDO $pdo)
    {
    }

    public function enregistrer(Commentaire $commentaire): bool
    {

        $commentaire->setDateCreation(new DateTime());
        $contenu = $commentaire->getContent();
        $dateCreation = $commentaire->getDateCreation()->format("Y-m-d H:i:s");
        $idUser = $commentaire->getIdUser();
        $idPost = $commentaire->getIdPost();
        $isValid = $commentaire->isValid();

        $query = $this->pdo->prepare('INSERT INTO `commentaires` (contenu, datePublication, idUser, idPost, isValide)
                                        VALUE (:contenu, :datePublication, :idUser, :idPost, :isValid)');
        $query->bindParam(':contenu', $contenu, PDO::PARAM_STR);
        $query->bindParam(':datePublication', $dateCreation, PDO::PARAM_STR);
        $query->bindParam(':idUser', $idUser, PDO::PARAM_INT);
        $query->bindParam(':idPost', $idPost, PDO::PARAM_INT);
        $query->bindParam(':isValid', $isValid, PDO::PARAM_INT);
        return $query->execute();
    }

    public function findCommentPending(): array
    {
        $query = $this->pdo->prepare('
            SELECT c.*, u.pseudo 
            FROM `commentaires` AS c 
                JOIN users AS u 
                    ON c.idUser = u.id
            WHERE isValide = 0');

        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findCommentByPost(int $idPost): array
    {
        $query = $this->pdo->prepare('
            SELECT c.*, u.pseudo 
            FROM `commentaires` AS c 
                JOIN users AS u ON c.idUser = u.id 
            WHERE idPost = :idPost AND isValide = true');
            
        $query->bindParam(':idPost', $idPost, PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteComment(int $id): bool
    {
        $query = $this->pdo->prepare('DELETE FROM `commentaires` WHERE idComment = :id');
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        return $query->execute();
    }

    public function commentValidate(int $id): bool
    {
        $query = $this->pdo->prepare('UPDATE `commentaires` SET isValide = true WHERE idComment = :id');
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        return $query->execute();
    }
}
