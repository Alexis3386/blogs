<?php

use App\Entity\Blogpost;

require_once('init.php');

$target_dir = "assets/img";


if ($user == null or !$user->isadmin()) {
    header('Location: /');
    exit();
}


if (isset($_POST) && !empty($_POST)) {
    var_dump($_POST);
    var_dump($_FILES);
    $lastId = $blogpostRepository->enregistrePost($_POST['titre'], $_POST['chapo'], $_POST['content']);
    if (isset($_POST['categorie'])) {
        $categorieRepository->associeCategorie($_POST['categorie'], $lastId);
    }
}

$categories = $categorieRepository->returnAllcategorie();

render('addPost.twig', ['categories' => $categories]);
