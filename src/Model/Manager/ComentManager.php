<?php

namespace App\Model\Manager;

use App\Framework\BaseManager;
use App\Model\Entity\Commentaire;
use DateTime;
use Exception;
use PDO;

class ComentManager extends BaseManager
{
    public function __construct($datasource)
    {
        parent::__construct("commentaires", "Commentaire", $datasource);
    }

    /**
     * find comment by post
     *
     * @param integer $idPost
     * @return Commentaire[]
     * @throws Exception
     */
    public function getCommentByPost(int $idPost): array
    {
        $commentsArray = [];
        $query = $this->_bdd->prepare('
            SELECT c.*, u.pseudo 
            FROM `commentaires` AS c 
                JOIN users AS u ON c.idUser = u.id 
            WHERE idPost = :idPost AND isValide = true');

        $query->bindParam(':idPost', $idPost, PDO::PARAM_INT);
        $query->execute();

        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $comment) {
            $commentsArray[] = new Commentaire(
                $comment['contenu'],
                $comment['idPost'],
                $comment['idUser'],
                $comment['isValide'],
                new Datetime($comment['datePublication']),
                $comment['idComment'],
                $comment['pseudo']
            );
        };
        return $commentsArray;
    }

    public function enregistrer(Commentaire $commentaire): bool
    {

        $commentaire->setDateCreation(new DateTime());
        $contenu = $commentaire->getContent();
        $dateCreation = $commentaire->getDateCreation()->format("Y-m-d H:i:s");
        $idUser = $commentaire->getIdUser();
        $idPost = $commentaire->getIdPost();
        $isValid = $commentaire->isValide();

        $query = $this->_bdd->prepare('INSERT INTO `commentaires` (contenu, datePublication, idUser, idPost, isValide)
                                        VALUE (:contenu, :datePublication, :idUser, :idPost, :isValid)');
        $query->bindParam(':contenu', $contenu, PDO::PARAM_STR);
        $query->bindParam(':datePublication', $dateCreation, PDO::PARAM_STR);
        $query->bindParam(':idUser', $idUser, PDO::PARAM_INT);
        $query->bindParam(':idPost', $idPost, PDO::PARAM_INT);
        $query->bindParam(':isValid', $isValid, PDO::PARAM_INT);
        return $query->execute();
    }

    public function deleteComment(int $id): bool
    {
        $query = $this->_bdd->prepare('DELETE FROM `commentaires` WHERE idComment = :id');
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        return $query->execute();
    }

    public function findCommentPending(): array
    {
        $commentsArray = [];

        $query = $this->_bdd->prepare('
            SELECT c.*, u.pseudo 
            FROM `commentaires` AS c 
                JOIN users AS u 
                    ON c.idUser = u.id
            WHERE isValide = 0');

        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $comment) {
            $commentsArray[] = new Commentaire(
                $comment['contenu'],
                $comment['idPost'],
                $comment['idUser'],
                $comment['isValide'],
                new Datetime($comment['datePublication']),
                $comment['idComment'],
                $comment['pseudo']
            );
        };
        return $commentsArray;
    }

    public function comentValidate(int $id): bool
    {
        $query = $this->_bdd->prepare('UPDATE `commentaires` SET isValide = true WHERE idComment = :id');
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        return $query->execute();
    }
}