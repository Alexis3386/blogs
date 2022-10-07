<?php

namespace App\Repository;

use App\Entity\Blogpost;
use App\Entity\Categorie;
use PDO;

class CategorieRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function returnAllcategorie(): array
    {
        $result = [];
        $sth = $this->pdo->prepare("SELECT * from categorie");
        $sth->execute();

        $categories = $sth->fetchAll(PDO::FETCH_ASSOC);

        foreach ($categories as $categorie) {
            $result[] = new Categorie($categorie['idCategorie'], $categorie['libelle']);
        }

        return $result;
    }

    public function associeCategorie(array $categories, int $id)
    {
        foreach ($categories as $categorie) {
            $categorie = intval($categorie);
            $query = $this->pdo->prepare("INSERT INTO `categorieblogpost` (idcategorie, idblogpost) VALUES (:idcategorie, :idblogpost)");
            $query->bindParam(':idcategorie', $categorie, PDO::PARAM_INT);
            $query->bindParam(':idblogpost', $id, PDO::PARAM_INT);
            $query->execute();
        }
    }

    public function categorieByPost(Blogpost $post): array
    {
        $idPost = $post->getId();
        $query = $this->pdo->prepare("SELECT * FROM `categorie`c INNER JOIN  `categorieblogpost`c2 ON 
        c.idCategorie = c2.idcategorie WHERE c2.idblogpost = :idPost");
        $query->bindParam(':idPost', $idPost, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
