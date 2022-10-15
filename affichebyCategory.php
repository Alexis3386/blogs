<?php
require_once('init.php');

if( isset($_GET['categorie']) && !empty($_GET['categorie'])) {
    $categorieId = $_GET['categorie'];
    $currentPage = (int)($_GET['page'] ?? 1);
    if ($currentPage <= 0) {
        throw new Exception('Numéro de page invalide');
    }
    $postTrie = $blogpostRepository->findPostbyCategory($categorieId, $currentPage);
    $categorie = $categorieRepository->findById($categorieId);
    if ($postTrie === []) {
        $_SESSION['notification']['notice'] = 'Aucun post dans la categorie' . 
        header('location: /');
        exit();
    } 
    render('postByCategorie.twig', ['postTrie' => $postTrie, 'categorie' => $categorie]);
}
