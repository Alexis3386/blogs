<?php

namespace App\Model\Manager;

use App\Framework\BaseManager;
use App\Model\Entity\Blogpost;
use DateTime;
use PDO;

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
     * @throws \Exception
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
        foreach($posts as $post) {
            $result[] = new Blogpost($post['titre'],
                $post['chapo'],
                $post['content'],
                $post['idAuthor'],
                $post['slug'],
                $post['idPost'],
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
            $count = (int)$this->_bdd->query("SELECT count(bp.idPost) 
                                        FROM blogpost as bp 
                                        INNER JOIN categorieblogpost as cbp 
                                        ON bp.idPost = cbp.idblogpost 
                                        INNER JOIN categorie as c 
                                        ON c.idCategorie = cbp.idcategorie 
                                        WHERE c.idCategorie = $idCategorie;")->fetch(PDO::FETCH_NUM)[0];
        } else {
            $count = (int)$this->_bdd->query('SELECT COUNT(idPost) FROM blogpost LIMIT 1;')->fetch(PDO::FETCH_NUM)[0];
        }
        return $count;
    }

    public function pagination(int $currentPage): int
    {
        $offset = self::NB_POSTS_PER_PAGE * ($currentPage - 1);
        $pages = $this->countNbpage();
        return $offset;
    }
}