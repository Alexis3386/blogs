<?php
require('init.php');


if (isset($_GET['delete']) && $_GET['delete'] === 'true' && !empty($_GET['idPost'])) {
    $idPost = $_GET['idPost'];
    $del = $blogpostRepository->delete($idPost);
    if ($del->rowCount() > 0) {
        $_SESSION['notification']['notice'] = 'Le post a bien été supprimé';
    }
}

$nbPost = $blogpostRepository->countNbPost();
$currentPage = (int)($_GET['page'] ?? 1);
$nbpages = $blogpostRepository->countNbpage();
$posts = $blogpostRepository->findPostWithPagination($currentPage);


render('home.twig', [
    'nbPost' => $nbPost,
    'posts' => $posts,
    'currentPage' => $currentPage,
    'nbpages' => $nbpages,
]);
