<?php


namespace App\Repository;

use App\Entity\Blogpost;
use App\Util;
use DateTime;
use PDO;

class BlogpostRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function enregistrer(Blogpost $post): Blogpost
    {
        $this->updateSlug($post);
        // $date = date("Y-m-d H:i:s");
        $post->setDateCreation(new DateTime());
        $post->setDateModification($post->getDateCreation());
        $titre = $post->getTitre();
        $chapo = $post->getChapo();
        $content = $post->getContent();
        $slug = $post->getSlug();
        $dateCreation = $post->getDateCreation()->format("Y-m-d H:i:s");
        $dateModification = $post->getDateModification()->format("Y-m-d H:i:s");
        $authorId = $post->getAuthorId();
        $imagePrincipale = $post->getImages();


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
        $query->bindParam(':dateCreation', $dateCreation, PDO::PARAM_STR);
        $query->bindParam(':dateModification', $dateModification, PDO::PARAM_STR);
        $query->bindParam(':idAuthor', $authorId, PDO::PARAM_INT);
        $query->bindParam(':idImagePrincipale', $imagePrincipale, PDO::PARAM_INT);

        $query->execute();
        $post->setId($this->pdo->lastInsertId());

        return $post;
    }

    public function updateSlug(Blogpost $post): void
    {
        // Tester si le slug existe
        $slug = Util::slugify($post->getTitre());
        if ($post->getId() !== null) {
            $query = $this->pdo->prepare("SELECT slug FROM blogpost WHERE slug = :slug AND idPost != :id");
            $query->bindParam(':id', $id, PDO::PARAM_STR);
        } else {
            $query = $this->pdo->prepare("SELECT slug FROM blogpost WHERE slug = :slug");
        }
        $query->bindParam(':slug', $slug, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        // tant qu'on a un resultat on modifie le slug
        $n = 0;
        while ($result !== false) {
            $n++;
            if ($post->getId() !== null) {
                $query = $this->pdo->prepare("SELECT slug FROM blogpost WHERE slug = :slug AND idPost != :id");
                $query->bindParam(':id', $id, PDO::PARAM_STR);
            } else {
                $query = $this->pdo->prepare("SELECT slug FROM blogpost WHERE slug = :slug");
            }
            $query->bindParam(':slug', $slug . $n, PDO::PARAM_STR);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
        }
        if ($n > 0) {
            $post->setSlug($slug . $n);
        }
        
        $post->setSlug($slug);
    }

    public function recuperePost(int $id): Blogpost
    {
        $query = $this->pdo->prepare('SELECT * FROM blogpost WHERE idPost = :id');
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        if ($query->execute()) {
            $result = $query->fetch(PDO::FETCH_ASSOC);
            $post = new Blogpost(
                $result['idPost'],
                $result['titre'],
                $result['slug'],
                $result['chapo'],
                $result['content'],
                $result['idAuthor'],
                new DateTime($result['dateCreation']),
                new DateTime($result['dateMiseAJour']),
                $result['idImagePrincipale']
            );
        }
        return $post;
    }

    public function readLastPost(int $limit): array
    {
        $query = $this->pdo->prepare('SELECT * FROM blogpost ORDER BY `dateCreation` DESC limit :limit');
        $query->bindParam(':limit', $limit, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}
