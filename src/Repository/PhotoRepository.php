<?php

namespace App\Repository;

use App\Entity\Blogpost;
use PDO;

class PhotoRepository {

    public function __construct(private PDO $pdo)
    {
    }

    public function enregistrer(string $path, int $idPost): bool {
        $query = $this->pdo->prepare('INSERT INTO `photos` (path, idPost) VALUE (:path, :idPost)');
        $query->bindParam(':path', $path, PDO::PARAM_STR);
        $query->bindParam(':idPost', $idPost, PDO::PARAM_INT);
        return $query->execute();
    }

    public function recuperPostImage(Blogpost $post) {
        $idPost = $post->getId();
        $query = $this->pdo->prepare('SELECT * FROM `photos` WHERE idPost = :idPost');
        $query->bindParam(':idPost', $idPost, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}