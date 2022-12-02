<?php


namespace App\Repository;

use App\Entity\Blogpost;
use App\Util;
use DateTime;
use PDO;
use PDOStatement;

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


        $query = $this->pdo->prepare(
            "INSERT INTO `blogpost` (titre, 
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
                                                :idAuthor)"
        );
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

    public function recuperePost(int $id): ?Blogpost
    {
        $query = $this->pdo->prepare('SELECT * FROM blogpost WHERE idPost = :id');
        $query->bindParam(':id', $id, PDO::PARAM_INT);

        if (!$query->execute()) {
            return null;
        }

        $result = $query->fetch(PDO::FETCH_ASSOC);
        if ($result === false) {
            return null;
        }

        return new Blogpost(
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
        $query = $this->pdo->prepare(
            "SELECT bp.* FROM blogpost as bp 
                                        INNER JOIN categorieblogpost as cbp
                                        ON bp.idPost = cbp.idblogpost
                                        INNER JOIN categorie as c
                                        ON c.idCategorie = cbp.idcategorie
                                        WHERE c.idCategorie = :idCategorie
                                        ORDER BY dateCreation 
                                        DESC LIMIT " .  self::NB_POSTS_PER_PAGE . "
                                        OFFSET $offset"
        );
        $query->bindParam(':idCategorie', $idCategorie, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function findPostWithPagination(int $currentPage): array
    {
        $offset = $this->pagination($currentPage);
        $query = $this->pdo->prepare(
            "SELECT bp.*, us.pseudo FROM blogpost as bp 
                                        INNER JOIN users as us
                                        ON bp.idAuthor = us.id
                                        ORDER BY dateCreation 
                                        DESC LIMIT " . self::NB_POSTS_PER_PAGE . " OFFSET $offset"
        );
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function countNbpage(?int $idCategorie = null): float
    {
        $count = $this->countNbPost($idCategorie);
        return ceil($count / self::NB_POSTS_PER_PAGE);
    }

    public function countNbPost(?int $idCategorie = null): int
    {
        if ($idCategorie !== null) {
            $idCategorie = intval($idCategorie);
            $count = (int)$this->pdo->query(
                "SELECT count(bp.idPost) 
                                        FROM blogpost as bp 
                                        INNER JOIN categorieblogpost as cbp 
                                        ON bp.idPost = cbp.idblogpost 
                                        INNER JOIN categorie as c 
                                        ON c.idCategorie = cbp.idcategorie 
                                        WHERE c.idCategorie = $idCategorie;"
            )->fetch(PDO::FETCH_NUM)[0];
        } else {
            $count = (int)$this->pdo->query('SELECT COUNT(idPost) FROM blogpost LIMIT 1;')->fetch(PDO::FETCH_NUM)[0];
        }
        return $count;
    }

    public function pagination(int $currentPage): int
    {
        $offset = self::NB_POSTS_PER_PAGE * ($currentPage - 1);
        $pages = $this->countNbpage();
        // if ($currentPage > $pages) {
        //     throw new Exception('Cette page n\'existe pas');
        // }
        return $offset;
    }

    public function delete(int $idPost): PDOStatement
    {
        $query = $this->pdo->prepare('DELETE FROM `blogpost` WHERE idPost = :idPost');
        $query->bindValue(':idPost', $idPost, PDO::PARAM_INT);
        $query->execute();
        return $query;
    }

    public function updatePost(Blogpost $post): Blogpost
    {
        $this->updateSlug($post);
        $post->setDateModification(new DateTime());
        $titre = $post->getTitre();
        $chapo = $post->getChapo();
        $content = $post->getContent();
        $slug = $post->getSlug();
        $dateMiseajour = $post->getDateModification()->format("Y-m-d H:i:s");
        $idAuthor = $post->getAuthorId();
        $idPost = $post->getId();

        $query = $this->pdo->prepare(
            'UPDATE blogpost 
                                SET titre = :titre, 
                                    chapo = :chapo, 
                                    content = :content, 
                                    slug = :slug,
                                    datemiseajour = :datemiseajour ,
                                    idAuthor = :idAuthor
                                WHERE idPost = :idPost'
        );

        $query->bindParam(':titre', $titre, PDO::PARAM_STR);
        $query->bindParam(':chapo', $chapo, PDO::PARAM_STR);
        $query->bindParam(':content', $content, PDO::PARAM_STR);
        $query->bindParam(':slug', $slug, PDO::PARAM_STR);
        $query->bindParam(':datemiseajour', $dateMiseajour, PDO::PARAM_STR);
        $query->bindParam(':idAuthor', $idAuthor, PDO::PARAM_INT);
        $query->bindParam(':idPost', $idPost, PDO::PARAM_INT);
        $query->execute();

        return $post;
    }
}
