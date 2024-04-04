<?php

use App\Framework\Router;
use App\Framework\HttpRequest;

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
try {
    $posts = $blogpostRepository->findPostWithPagination($currentPage);
} catch (Exception $e) {
}

try
{
    $httpRequest = new HttpRequest();
    $router = new Router();
    $httpRequest->setRoute($router->findRoute($httpRequest));
    var_dump($httpRequest);
}
catch(Exception $e)
{
    echo $e->getMessage();
}
render('home.twig', [
    'nbPost' => $nbPost,
    'posts' => $posts,
    'currentPage' => $currentPage,
    'nbpages' => $nbpages,
]);
