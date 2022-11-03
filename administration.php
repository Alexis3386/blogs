<?php

require_once('init.php');

if ($user == null or !$user->isadmin()) {
    render('administration/administrationSignin.twig');
    exit();
}

$currentPage = (int)($_GET['page'] ?? 1);
$nbpages = $blogpostRepository->countNbpage();
$posts = $blogpostRepository->findPostWithPagination($currentPage);
if (isset($_GET['delete']) && $_GET['delete'] === 'true' && !empty($_GET['idPost'])) {
    $idPost = $_GET['idPost'];
    $del = $blogpostRepository->delete($idPost);
    if ($del->rowCount() > 0) {
        $_SESSION['notification']['notice'] = 'Le post a bien été supprimé';
    }
}

render('administration/dashboard.twig', ['posts' => $posts, 'currentPage' => $currentPage, 'nbpages' => $nbpages]);
