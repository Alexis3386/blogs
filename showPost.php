<?php

require 'init.php';

if (isset($_GET['idPost'])) {
    $id = $_GET['idPost'];
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
