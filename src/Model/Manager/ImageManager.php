<?php

namespace App\Model\Manager;


use App\Framework\BaseManager;
use App\Model\Entity\Blogpost;
use PDO;

class ImageManager extends BaseManager
{
    public function __construct($datasource)
    {
        parent::__construct("image", "Image", $datasource);
    }

    public function getPostImage(Blogpost $post): array
    {
        $idPost = $post->getId();
        $query = $this->_bdd->prepare('SELECT * FROM `image` INNER JOIN `imageblogpost` AS imb ON imb.idimage = image.idimage INNER JOIN `blogpost` as bp ON imb.idblogpost = bp.id WHERE bp.id = :idPost;');
        $query->bindParam(':idPost', $idPost, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function enregistrer(string $path, Blogpost $post, bool $update): bool
    {

        $idPost = $post->getId();

        $query = $this->_bdd->prepare('INSERT INTO `image` (path) VALUE (:path)');
        $query->bindParam(':path', $path, PDO::PARAM_STR);
        $query->execute();
        $idImage = $this->_bdd->lastInsertId();

        if ($update) {
            $query = $this->_bdd->prepare("DELETE FROM `imageblogpost` WHERE idblogpost = :idblogpost");
            $query->bindParam(':idblogpost', $idPost);
            $query->execute();
        }

        $query = $this->_bdd->prepare('INSERT INTO `imageblogpost` (idimage, idblogpost) VALUE (:idimage, :idblogpost)');
        $query->bindParam(':idimage', $idImage, PDO::PARAM_INT);
        $query->bindParam(':idblogpost', $idPost, PDO::PARAM_INT);
        return $query->execute();
    }

    public function deleteImage(Blogpost $post): bool
    {
        $query = $this->_bdd->prepare('DELETE image 
            FROM image, `imageblogpost`, `blogpost` 
            WHERE image.idimage = imageblogpost.idimage
            AND imageblogpost.idblogpost = :idBlogpost');

        $idPost = $post->getId();
        $query->bindParam(':idBlogpost', $idPost, PDO::PARAM_INT);
        $query->execute();
        return $query->rowCount() > 0;
    }

    public function deleteImageBlogPost(Blogpost $post): bool
    {
        $idPost = $post->getId();
        $query = $this->_bdd->prepare('DELETE FROM `imageblogpost` WHERE idblogpost = :idPost');
        $query->bindParam(':idPost', $idPost, PDO::PARAM_INT);
        return $query->execute();
    }
}