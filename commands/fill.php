<?php
require_once('../init.php');

for ($i= 0; $i < 50; $i++) {
    $pdo->exec("INSERT INTO `blogpost` SET titre = 'titre #$i', chapo = 'extrait du post n° #$i', content= 'content article n° #$i', slug='artice-$i', dateCreation='2022-10-14 09:53:25', idAuthor = 1 ");
}