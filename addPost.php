<?php

require_once 'init.php';

$upload_dir = "assets/img";

use App\Entity\Blogpost;


if ($user === null || $user->isadmin() === false) {
    header('Location: /');
    return;
}

if (isset($_POST) && empty($_POST) === false) {
    $post = new Blogpost(htmlspecialchars($_POST['titre'], double_encode: false), htmlspecialchars($_POST['chapo']), htmlspecialchars($_POST['content']), $user->getId());
    $post = $blogpostRepository->enregistrer($post);
    $slug = $blogpostRepository->updateSlug($post);

    if (isset($_POST['categorie'])) {
        $categorieRepository->associeCategorie($_POST['categorie'], $post, false);
    }

    enregistrementImage($photoRepository, $post, false);

    header('Location: /');
    $_SESSION['notification']['notice'] = 'Le post a bien été ajouté';
    return;
}

render(
    'addPost.twig',
    ['categories' => $categories,]
);
