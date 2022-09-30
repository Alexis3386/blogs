<?php

namespace App\Repository;

use PDO;

class PhotoRepository {

    public function __construct(private PDO $pdo)
    {
    }

    public function enregistrer(string $path, int $idPost) {
        $query = $this->pdo->prepare('INSERT INTO `photos` (path, idPost) VALUE (:path, :idPost)');
        $query->bindParam(':path', $path, PDO::PARAM_STR);
        $query->bindParam(':idPost', $idPost, PDO::PARAM_INT);
        return $query->execute();
    }   
}