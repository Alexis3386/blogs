<?php


namespace App\Repository;

use App\Entity\User;
use App\Entity\Blogpost;
use App\Util;

use PDO;

class BlogpostRepository
{
    public function __construct(private PDO $pdo, private User $user)
    {
    }

    public function enregistrePost(string $titre, string $chapo, string $content, int $idImage = 1): int
    {
        $slug = $this->updateSlug($titre);
        $date = date("Y-m-d H:i:s");
        $idAuthor = $this->user->getUserId();
        $query = $this->pdo->prepare("INSERT INTO `blogpost` (titre, 
                                                            chapo, 
                                                            content, 
                                                            slug, 
                                                            dateCreation, 
                                                            idAuthor, 
                                                            idImagePrincipale) 
                                        VALUES (:titre, 
                                                :chapo, 
                                                :content, 
                                                :slug, 
                                                :dateCreation, 
                                                :idAuthor, 
                                                :idImagePrincipale)");
        $query->bindParam(':titre', $titre, PDO::PARAM_STR);
        $query->bindParam(':chapo', $chapo, PDO::PARAM_STR);
        $query->bindParam(':content', $content, PDO::PARAM_STR);
        $query->bindParam(':slug', $slug, PDO::PARAM_STR);
        $query->bindParam(':dateCreation', $date, PDO::PARAM_STR);
        $query->bindParam(':idAuthor', $idAuthor, PDO::PARAM_INT);
        $query->bindParam(':idImagePrincipale', $idImage, PDO::PARAM_INT);
        $query->execute();
        return $this->pdo->lastInsertId();
    }

    public function updateSlug(string $titre): string
    {
        // Tester si le slug existe
        $slug = Util::slugify($titre);
        $query = $this->pdo->prepare("SELECT slug FROM blogpost WHERE slug = :slug");
        $query->bindParam(':slug', $slug, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        // tant qu'on a un resultat on modifie le slug
        $n = 1;
        while ($result) {
            $slug .= strval($n);
            $query = $this->pdo->prepare("SELECT slug FROM blogpost WHERE slug = :slug");
            $query->bindParam(':slug', $slug, PDO::PARAM_STR);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            $n += 1;
        }
        return $slug;
    }

    public function readLastPost(int $limit)
    {
    }
}