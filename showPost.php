<?php

use App\Model\Entity\Commentaire;


require 'init.php';

if (isset($_GET['idPost'])) {
    $id = $_GET['idPost'];
}

if (isset($_POST) && !empty($_POST['content']) && !empty($_POST['postId'])) {
    $commentaire = new Commentaire(
        $_POST['content'],
        $_POST['postId'],
        $user->getId(),
    );

    if ($commentaireRepository->enregistrer($commentaire)) {
        $_SESSION['notification']['notice'] = 'Votre commentaire a bien été enregistré, il attend la modération';
    }
}

if (isset($_POST['commentDel']) && isset($_POST['commentIdtoDel'])) {
    if ($commentaireRepository->deleteComment($_POST['commentIdtoDel'])) {
        $_SESSION['notification']['notice'] = 'Votre commentaire a bien été éffacé';
    }
}

$post = $blogpostRepository->recuperePost($id);
$postimage = $photoRepository->recuperPostImage($post);
$postcategories = $categorieRepository->categorieByPost($post);
$author = $userRepository->findAuthor($post);
$commentaires = $commentaireRepository->findCommentByPost($id);

render('showPost.twig', [
    'categories' => [],
    'commentaires' => $commentaires,
    'post' => $post,
    'image' => $postimage,
    'postcategories' => $postcategories,
    'author' => $author,
    'user' => $user
]);
