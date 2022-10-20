<?php


namespace App\Repository;

use App\Entity\Blogpost;
use App\Util;
use DateTime;
use PDO;

class BlogpostRepository
{
    const NB_POSTS_PER_PAGE = 12;

    public function __construct(private PDO $pdo)
    {
    }

    public function enregistrer(Blogpost $post): Blogpost
    {
        $this->updateSlug($post);
        $post->setDateCreation(new DateTime());
        $post->setDateModification($post->getDateCreation());
        $titre = $post->getTitre();
        $chapo = $post->getChapo();
        $content = $post->getContent();
        $slug = $post->getSlug();
        $dateCreation = $post->getDateCreation()->format("Y-m-d H:i:s");
        $dateMiseajour = $post->getDateModification()->format("Y-m-d H:i:s");
        $authorId = $post->getAuthorId();


        $query = $this->pdo->prepare("INSERT INTO `blogpost` (titre, 
                                                            chapo, 
                                                            content, 
                                                            slug, 
                                                            dateCreation,
                                                            datemiseajour,
                                                            idAuthor) 
                                        VALUES (:titre, 
                                                :chapo, 
                                                :content, 
                                                :slug, 
                                                :dateCreation,
                                                :datemiseajour,
                                                :idAuthor)");
        $query->bindParam(':titre', $titre, PDO::PARAM_STR);
        $query->bindParam(':chapo', $chapo, PDO::PARAM_STR);
        $query->bindParam(':content', $content, PDO::PARAM_STR);
        $query->bindParam(':slug', $slug, PDO::PARAM_STR);
        $query->bindParam(':dateCreation', $dateCreation, PDO::PARAM_STR);
        $query->bindParam(':datemiseajour', $dateMiseajour, PDO::PARAM_STR);
        $query->bindParam(':idAuthor', $authorId, PDO::PARAM_INT);

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
            $slug = $slug . $n;
            $query->bindParam(':slug', $slug, PDO::PARAM_STR);
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
                $result['titre'],
                $result['chapo'],
                $result['content'],
                intval($result['idAuthor']),
                $result['slug'],
                $result['idPost'],
                new DateTime($result['dateCreation']),
                new DateTime($result['dateMiseAJour']),
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

    public function findPostbyCategory(int $idCategorie, int $currentPage): array
    {
        $offset = $this->pagination($currentPage);
        $query = $this->pdo->prepare("SELECT bp.* FROM blogpost as bp 
                                        INNER JOIN categorieblogpost as cbp
                                        ON bp.idPost = cbp.idblogpost
                                        INNER JOIN categorie as c
                                        ON c.idCategorie = cbp.idcategorie
                                        WHERE c.idCategorie = :idCategorie
                                        ORDER BY dateCreation 
                                        DESC LIMIT " .  self::NB_POSTS_PER_PAGE . "
                                        OFFSET $offset");
        $query->bindParam(':idCategorie', $idCategorie, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function countNbpage()
    {
        $count = (int)$this->pdo->query('SELECT COUNT(idPost) FROM blogpost LIMIT 1')->fetch(PDO::FETCH_NUM)[0];
        return ceil($count / self::NB_POSTS_PER_PAGE);
    }

    public function pagination(int $currentPage)
    {
        $offset = self::NB_POSTS_PER_PAGE * ($currentPage - 1);
        $pages = $this->countNbpage();
        if ($currentPage > $pages) {
            throw new Exception('Cette page n\'existe pas');
        }
        return $offset;
    }
}
