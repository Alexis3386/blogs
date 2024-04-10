<?php

namespace App\Model\Manager;

use App\Framework\BaseManager;
use App\Model\Entity\Blogpost;
use App\Util;
use DateTime;
use Exception;
use PDO;
use PDOStatement;

class BlogPostManager extends BaseManager
{
    const int NB_POSTS_PER_PAGE = 12;

    public function __construct($datasource)
    {
        parent::__construct("blogpost", "Blogpost", $datasource);
    }

    /**
     * find post with pagination
     *
     * @param integer $currentPage
     * @return Blogpost[]
     * @throws Exception
     */
    public function findPostWithPagination(int $currentPage): array
    {
        $result = [];
        $offset = $this->pagination($currentPage);
        $query = $this->_bdd->prepare("SELECT bp.*, us.pseudo FROM blogpost as bp 
                                        INNER JOIN users as us
                                        ON bp.idAuthor = us.id
                                        ORDER BY dateCreation 
                                        DESC LIMIT " . self::NB_POSTS_PER_PAGE . " OFFSET $offset");
        $query->execute();
        $posts = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($posts as $post) {
            $result[] = new Blogpost($post['titre'],
                $post['chapo'],
                $post['content'],
                $post['idAuthor'],
                $post['slug'],
                $post['id'],
                new Datetime($post['dateCreation']),
                new Datetime($post['dateMiseAJour']));
        }
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
            $count = (int)$this->_bdd->query("SELECT count(bp.id) 
                                        FROM blogpost as bp 
                                        INNER JOIN categorieblogpost as cbp 
                                        ON bp.id = cbp.idblogpost 
                                        INNER JOIN categorie as c 
                                        ON c.idCategorie = cbp.idcategorie 
                                        WHERE c.idCategorie = $idCategorie;")->fetch(PDO::FETCH_NUM)[0];
        } else {
            $count = (int)$this->_bdd->query('SELECT COUNT(id) FROM blogpost LIMIT 1;')->fetch(PDO::FETCH_NUM)[0];
        }
        return $count;
    }

    public function pagination(int $currentPage): int
    {
        $offset = self::NB_POSTS_PER_PAGE * ($currentPage - 1);
        $pages = $this->countNbpage();
        return $offset;
    }

    /**
     * @throws Exception
     */
    public function getPostById(int $id): ?Blogpost
    {
        $query = $this->_bdd->prepare('SELECT * FROM blogpost WHERE id = :id');
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
            $result['id'],
            new DateTime($result['dateCreation']),
            new DateTime($result['dateMiseAJour']),
        );
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


        $query = $this->_bdd->prepare("INSERT INTO `blogpost` (titre, 
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
        $post->setId($this->_bdd->lastInsertId());

        return $post;
    }

    public function updateSlug(Blogpost $post): void
    {
        // Tester si le slug existe
        $slug = Util::slugify($post->getTitre());
        if ($post->getId() !== null) {
            $query = $this->_bdd->prepare("SELECT slug FROM blogpost WHERE slug = :slug AND id != :id");
            $query->bindParam(':id', $id, PDO::PARAM_STR);
        } else {
            $query = $this->_bdd->prepare("SELECT slug FROM blogpost WHERE slug = :slug");
        }
        $query->bindParam(':slug', $slug, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        // tant qu'on a un resultat on modifie le slug
        $n = 0;
        while ($result !== false) {
            $n++;
            if ($post->getId() !== null) {
                $query = $this->_bdd->prepare("SELECT slug FROM blogpost WHERE slug = :slug AND id != :id");
                $query->bindParam(':id', $id, PDO::PARAM_STR);
            } else {
                $query = $this->_bdd->prepare("SELECT slug FROM blogpost WHERE slug = :slug");
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

    public function deletePost(int $id): PDOStatement
    {
        $query = $this->_bdd->prepare('DELETE FROM `blogpost` WHERE id = :id');
        $query->bindValue(':id', $id, PDO::PARAM_INT);
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

        $query = $this->_bdd->prepare(
            'UPDATE blogpost 
                                SET titre = :titre, 
                                    chapo = :chapo, 
                                    content = :content, 
                                    slug = :slug,
                                    datemiseajour = :datemiseajour ,
                                    idAuthor = :idAuthor
                                WHERE id = :idPost'
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

    public function findPostbyCategory(int $idCategorie, int $currentPage): array
    {
        $result = [];
        $offset = $this->pagination($currentPage);
        $query = $this->_bdd->prepare("SELECT bp.* FROM blogpost as bp
                                        INNER JOIN categorieblogpost as cbp
                                        ON bp.id = cbp.idblogpost
                                        INNER JOIN categorie as c
                                        ON c.idCategorie = cbp.idcategorie
                                        WHERE c.idCategorie = :idCategorie
                                        ORDER BY dateCreation
                                        DESC LIMIT " . self::NB_POSTS_PER_PAGE . "
                                        OFFSET $offset");
        $query->bindParam(':idCategorie', $idCategorie, PDO::PARAM_INT);
        $query->execute();
        $posts = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach($posts as $post) {
            $result[] = new Blogpost($post['titre'],
                $post['chapo'],
                $post['content'],
                $post['idAuthor'],
                $post['slug'],
                $post['id'],
                new Datetime($post['dateCreation']),
                new Datetime($post['dateMiseAJour']));
        }
        return $result;
    }
}