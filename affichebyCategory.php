<?php
require_once('init.php');

if( isset($_GET['categorie']) && !empty($_GET['categorie'])) {
    $categorieId = $_GET['categorie'];
    $currentPage = (int)($_GET['page'] ?? 1);
    if (!filter_var($currentPage, FILTER_VALIDATE_INT)) {
        throw new Exception('Numéro de page invalide');
    }
    $nbpages = $blogpostRepository->countNbpage($categorieId);
    if ($currentPage <= 0) {
        throw new Exception('Numéro de page invalide');
    }
    $postTrie = $blogpostRepository->findPostbyCategory($categorieId, $currentPage);
    $categorie = $categorieRepository->findById($categorieId);
    if ($postTrie === []) {
        $_SESSION['notification']['notice'] = 'Aucun post dans la categorie ' . $categorie->getLibelle();
        header('location: /');
        exit();
    } 
    render('postByCategorie.twig', ['postTrie' => $postTrie, 'categorie' => $categorie, 'currentPage' => $currentPage, 'nbpages' => $nbpages]);
}
