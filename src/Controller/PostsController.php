<?php

namespace App\Controller;

use App\Model\Entity\Blogpost;
use App\Model\Entity\Commentaire;
use App\Model\Manager\ImageManager;
use Exception;
use finfo;
use RuntimeException;

class PostsController extends BaseController
{
    public function showPost($id): void
    {
        $post = $this->_manager["App\Model\Manager\BlogPostManager"]->getPostById($id);
        $postimage = $this->_manager["App\Model\Manager\ImageManager"]->getPostImage($post);
        $commentaires = $this->_manager["App\Model\Manager\ComentManager"]->getCommentByPost($id);
        $categorie = $this->_manager["App\Model\Manager\CategoryManager"]->getCategoryByPost($post);
        $author = $this->_manager["App\Model\Manager\UserManager"]->findAuthor($post);
        $user = $this->getCurrentUser();

        $this->view('showPost.twig', ['commentaires' => $commentaires,
            'post' => $post,
            'image' => $postimage,
            'postcategories' => $categorie,
            'author' => $author,
            'user' => $user]);
    }

    /**
     * @throws Exception
     */
    public function showByCategory($categorie, $page = 1): void
    {
        if (!filter_var($page, FILTER_VALIDATE_INT)) {
            throw new Exception('Numéro de page invalide');
        }

        $nbpages = $this->_manager["App\Model\Manager\BlogPostManager"]->countNbpage($categorie);
        $postTrie = $this->_manager["App\Model\Manager\BlogPostManager"]->findPostbyCategory($categorie, $page);
        $categorie = $this->_manager["App\Model\Manager\CategoryManager"]->findById($categorie);
        if ($postTrie === []) {
            $_SESSION['notification']['notice'] = 'Aucun post dans la categorie ' . $categorie->getLibelle();
            header('location: /');
            return;
        }

        $this->view('postByCategorie.twig',
            [
                'postTrie' => $postTrie,
                'categorie' => $categorie,
                'currentPage' => $page,
                'nbpages' => $nbpages
            ]
        );
    }

    public function addComent($postId, $content): void
    {
        if ($this->getCurrentUser() !== null) {
            $commentaire = new Commentaire(
                $content,
                $postId,
                $this->getCurrentUser()->getId(),
            );

            if ($this->_manager["App\Model\Manager\ComentManager"]->enregistrer($commentaire)) {
                $_SESSION['notification']['notice'] = 'Votre commentaire a bien été enregistré, il attend la modération';
                header('location: /showPost?id=' . $postId);
            };
        }
    }

    public function addPost($titre, $chapo, $content, $categorie = null): void
    {
        if ($this->getCurrentUser() === null || !$this->getCurrentUser()->getisadmin()) {
            header('location: /connexion');
            return;
        }

        $post = new Blogpost(
            htmlspecialchars($titre, double_encode: false),
            htmlspecialchars($chapo),
            htmlspecialchars($content),
            $this->getCurrentUser()->getId()
        );

        $post = $this->_manager["App\Model\Manager\BlogPostManager"]->enregistrer($post);
        $this->_manager["App\Model\Manager\BlogPostManager"]->updateSlug($post);

        if ($categorie !== null) {
            $this->_manager["App\Model\Manager\CategoryManager"]->associeCategorie($categorie, $post, false);
        }

        $this->enregistrementImage($this->_manager["App\Model\Manager\ImageManager"], $post, false);

        header('location: /');
        exit();
    }

    public function showAddPost(): void
    {
        if ($this->getCurrentUser() === null || !$this->getCurrentUser()->getisadmin()) {
            header('location: /connexion');
            return;
        }
        $this->view(
            'addPost.twig',
        );
    }

    public function deletePost($idPost): void
    {
        $post = $this->_manager["App\Model\Manager\BlogPostManager"]->deletePost($idPost);
        $_SESSION['notification']['notice'] = 'Le post a été supprimé';
        header('location: /');
    }

    public function editPost($id, $titre, $chapo, $content, $categorie = null): void
    {
        if ($this->getCurrentUser() === null || !$this->getCurrentUser()->getisadmin()) {
            header('location: /connexion');
            return;
        }

        $post = $this->_manager["App\Model\Manager\BlogPostManager"]->getPostById($id);

        if ($post === null) {
            header('HTTP/1.0 404 Not Found');
            return;
        }

        $post->setTitre(htmlspecialchars($titre));
        $post->setChapo(htmlspecialchars($chapo));
        $post->setContent(htmlspecialchars($content));
        $post = $this->_manager["App\Model\Manager\BlogPostManager"]->updatePost($post);

        if ($categorie !== null) {
            $this->_manager["App\Model\Manager\CategoryManager"]->associeCategorie($categorie, $post, true);
        }

        $this->enregistrementImage($this->_manager["App\Model\Manager\ImageManager"], $post, true);

        $_SESSION['notification']['notice'] = 'Le post a été modifié';
        header('Location: /');

    }

    public function showEditPost($idPost): void
    {

        if ($this->getCurrentUser() === null || !$this->getCurrentUser()->getisadmin()) {
            header('location: /connexion');
            return;
        }

        $post = $this->_manager["App\Model\Manager\BlogPostManager"]->getPostById($idPost);

        $this->view(
            'updatePost.twig',
            ['postUpdate' => $post]
        );
    }

    function enregistrementImage(ImageManager $photoRepository, Blogpost $post, bool $update): void
    {
        try {
            // Undefined | Multiple Files | $_FILES Corruption Attack
            // If this request falls under any of them, treat it invalid.
            if (
                !isset($_FILES['image_principale']['error']) ||
                in_array('error', $_FILES['image_principale'])
            ) {
                throw new RuntimeException('Invalid parameters.');
            }

            // Check $_FILES['image_principale']['error'] value.
            switch ($_FILES['image_principale']['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    if ($photoRepository->deleteImage($post)) {
                        $photoRepository->deleteImageBlogPost($post);
                    }
                    return;
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new RuntimeException('Exceeded filesize limit.');
                default:
                    throw new RuntimeException('Unknown errors.');
            }

            // You should also check filesize here.
            if ($_FILES['image_principale']['size'] > 1000000) {
                throw new RuntimeException('Exceeded filesize limit.');
            }

            // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
            // Check MIME Type by yourself.
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            if (false === $ext = array_search(
                    $finfo->file($_FILES['image_principale']['tmp_name']),
                    array(
                        'jpg' => 'image/jpeg',
                        'png' => 'image/png',
                        'gif' => 'image/gif',
                        'webp' => 'image/webp'
                    ),
                    true
                )) {
                throw new RuntimeException('Invalid file format.');
            }

            // You should name it uniquely.
            // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
            // On this example, obtain safe unique name from its binary data.
            $file_name = sprintf(
                './assets/img/%s.%s',
                sha1_file($_FILES['image_principale']['tmp_name']),
                $ext
            );
            if (!move_uploaded_file(
                $_FILES['image_principale']['tmp_name'],
                $file_name
            )) {
                throw new RuntimeException('Failed to move uploaded file.');
            } else {
                $photoRepository->enregistrer($file_name, $post, $update);
            }
        } catch (RuntimeException $e) {

            echo $e->getMessage();
        }
    }
}
