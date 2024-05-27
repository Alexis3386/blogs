<?php

namespace App\Model\Manager;

use App\Framework\BaseManager;
use App\Model\Entity\Blogpost;
use App\Model\Entity\Categorie;
use PDO;

class CategoryManager extends BaseManager
{
    public function __construct($datasource)
    {
        parent::__construct("categorie", "Categorie", $datasource);
    }

    public function getCategoryByPost(Blogpost $post): array
    {
        $idPost = $post->getId();
        $query = $this->_bdd->prepare("SELECT * FROM `categorie`c INNER JOIN `categorieblogpost`c2 ON 
        c.idCategorie = c2.idcategorie WHERE c2.idblogpost = :idPost");
        $query->bindParam(':idPost', $idPost, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function associeCategorie(array $categories, Blogpost $post, bool $update) : void
    {
        $idPost = $post->getId();
        if ($update) {
            $query = $this->_bdd->prepare("DELETE FROM `categorieblogpost` WHERE idblogpost = :idblogpost");
            $query->bindParam(':idblogpost', $idPost);
            $query->execute();
        }
        foreach ($categories as $categorie) {
            $categorie = intval($categorie);
            $query = $this->_bdd->prepare("INSERT INTO `categorieblogpost` (idcategorie, idblogpost) VALUES (:idcategorie, :idblogpost)");
            $query->bindParam(':idcategorie', $categorie, PDO::PARAM_INT);
            $query->bindParam(':idblogpost', $idPost, PDO::PARAM_INT);
            $query->execute();
        }
    }

    public function findById(int $id): ?Categorie
    {
        $query = $this->_bdd->prepare('SELECT * FROM categorie WHERE idCategorie = :idCategorie');
        $query->bindParam('idCategorie', $id, PDO::PARAM_INT);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);
        if ($result !== []) {
            return new Categorie($result['idCategorie'], $result['libelle']);
        }
        return null;
    }

    public function getAllcategorie(): array
    {
        $result = [];
        $sth = $this->_bdd->prepare("SELECT * from categorie");
        $sth->execute();

        $categories = $sth->fetchAll(PDO::FETCH_ASSOC);

        foreach ($categories as $categorie) {
            $result[] = new Categorie($categorie['idCategorie'], $categorie['libelle']);
        }

        return $result;
    }
}
