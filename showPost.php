<?php

use App\Entity\Commentaire;

require 'init.php';

if (isset($_GET['idPost'])) {
    $id = $_GET['idPost'];
}

if (isset($_POST) && !empty($_POST)) {
    $commentaire = new Commentaire(
        $_POST['content'],
        $_POST['postId'],
        $user->getId()
 );
 $commentaireRepository->enregistrer($commentaire);

}


$post = $blogpostRepository->recuperePost($id);
$postimage = $photoRepository->recuperPostImage($post);
$postcategories = $categorieRepository->categorieByPost($post);
$author = $userRepository->findAuthor($post);

render('showPost.twig', [
    'post' => $post,
    'image' => $postimage,
    'categories' => $postcategories,
    'author' => $author,
]);
