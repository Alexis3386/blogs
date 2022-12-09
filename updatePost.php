<?php

require_once 'init.php';

$upload_dir = "assets/img";


if ($user === null || $user->isadmin() === false) {
    header('Location: /');
    exit();
}

if (!isset($_GET['idPost']) || empty($_GET['idPost'])) {
    header('HTTP/1.0 404 Not Found');
    exit();
}

$postUpdate = $blogpostRepository->recuperePost($_GET['idPost']);
if ($postUpdate === null) {
    header('HTTP/1.0 404 Not Found');
    exit();
}

if (isset($_POST) && !empty($_POST)) {
    $postUpdate->setTitre($_POST['titre']);
    $postUpdate->setChapo($_POST['chapo']);
    $postUpdate->setContent($_POST['content']);
    $postUpdate = $blogpostRepository->updatePost($postUpdate);

    if (isset($_POST['categorie'])) {
        $categorieRepository->associeCategorie($_POST['categorie'], $postUpdate, true);
    }

    enregistrementImage($photoRepository, $postUpdate);

    header('Location: /');
    $_SESSION['notification']['notice'] = 'Le post a bien été modifié';
    exit();
}

render(
    'updatePost.twig',
    [
        'categories' => $categories,
        'postUpdate' => $postUpdate,
        'categoriePostUpdates' => $categorieRepository->categorieByPost($postUpdate),
    ]
);
