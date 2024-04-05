<?php

namespace App\Repository;

use App\Model\Entity\Blogpost;
use PDO;

class PhotoRepository {

    public function __construct(private PDO $pdo)
    {
    }

    public function enregistrer(string $path, Blogpost $post, bool $update): bool {

        $idPost = $post->getId();

        $query = $this->pdo->prepare('INSERT INTO `image` (path) VALUE (:path)');
        $query->bindParam(':path', $path, PDO::PARAM_STR);
        $query->execute();
        $idImage = $this->pdo->lastInsertId();

        if ($update) {
            $query = $this->pdo->prepare("DELETE FROM `imageblogpost` WHERE idblogpost = :idblogpost");
            $query->bindParam(':idblogpost', $idPost);
            $query->execute();
        }

        $query = $this->pdo->prepare('INSERT INTO `imageblogpost` (idimage, idblogpost) VALUE (:idimage, :idblogpost)');
        $query->bindParam(':idimage', $idImage, PDO::PARAM_INT);
        $query->bindParam(':idblogpost', $idPost, PDO::PARAM_INT);
        return $query->execute();
    }

    public function recuperPostImage(Blogpost $post): array {
        $idPost = $post->getId();
        $query = $this->pdo->prepare('SELECT * FROM `image` INNER JOIN `imageblogpost` AS imb ON imb.idimage = image.idimage INNER JOIN `blogpost` as bp ON imb.idblogpost = bp.idPost WHERE bp.idPost = :idPost;');
        $query->bindParam(':idPost', $idPost, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

}
