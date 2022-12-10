<?php

require_once 'init.php';

$upload_dir = "assets/img";


if ($user === null || $user->isadmin() === false) {
    header('Location: /');
    return;
}

if (!isset($_GET['idPost']) || empty($_GET['idPost'])) {
    header('HTTP/1.0 404 Not Found');
    return;
}

$postUpdate = $blogpostRepository->recuperePost($_GET['idPost']);
if ($postUpdate === null) {
    header('HTTP/1.0 404 Not Found');
    return;
}

if (isset($_POST) && !empty($_POST)) {
    $postUpdate->setTitre(htmlspecialchars($_POST['titre']));
    $postUpdate->setChapo(htmlspecialchars($_POST['chapo']));
    $postUpdate->setContent(htmlspecialchars($_POST['content']));
    $postUpdate = $blogpostRepository->updatePost($postUpdate);

    if (isset($_POST['categorie'])) {
        $categorieRepository->associeCategorie($_POST['categorie'], $postUpdate, true);
    }

    enregistrementImage($photoRepository, $postUpdate, true);

    header('Location: /');
    $_SESSION['notification']['notice'] = 'Le post a bien été modifié';
    return;
}

render(
    'updatePost.twig',
    [
        'categories' => $categories,
        'postUpdate' => $postUpdate,
        'categoriePostUpdates' => $categorieRepository->categorieByPost($postUpdate),
    ]
);
