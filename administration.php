<?php

require_once 'init.php';

if ($user === null || $user->isadmin() === false) {
    render('administration/administrationSignin.twig');
    return;
}

if (isset($_POST['commentDel']) && isset($_POST['commentIdtoDel'])) {
    if ($commentaireRepository->deleteComment(htmlspecialchars($_POST['commentIdtoDel']))) {
        $_SESSION['notification']['notice'] = 'Votre commentaire a bien été éffacé';
    }
}

if (isset($_POST['commentToValidate']) && empty($_POST['commentToValidate']) === false) {
    if ($commentaireRepository->commentValidate(htmlspecialchars($_POST['commentToValidate']))) {
        $_SESSION['notification']['notice'] = 'Le commentaire a été validé';
    }
}

$commentsPending = $commentaireRepository->findCommentPending();

render(
    'administration/adminComments.twig',
    [
        'commentsPending' => $commentsPending,
        'categories' => [],
    ]
);
