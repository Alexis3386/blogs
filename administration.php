<?php

require_once('init.php');

if ($user == null or !$user->isadmin()) {
    render('administration/administrationSignin.twig');
    exit();
}

$nbPost = $blogpostRepository->countNbPost();
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

if (isset($_POST['commentDel']) && isset($_POST['commentIdtoDel'])) {
    if($commentaireRepository->deleteComment($_POST['commentIdtoDel'])) {
        $_SESSION['notification']['notice'] = 'Votre commentaire a bien été éffacé';
    }
}

render('administration/administrationPosts.twig', 
    [   'nbPost'=> $nbPost ,
        'posts' => $posts, 
        'currentPage' => $currentPage, 
        'nbpages' => $nbpages,
        'commentaireRepository' => $commentaireRepository,]
    );
