<?php

use App\Entity\Blogpost;

require_once(dirname(dirname(__FILE__)) . '/init.php');

// use the factory to create a Faker\Generator instance
$faker = Faker\Factory::create();
// generate data by calling methods
//todo créer des fonctions



$titre = $faker->sentence(rand(5, 10));
$chapo = $faker->text(rand(75, 150));
$content = $faker->text(rand(250, 500));
$idAuthor = 1;

$post = new Blogpost($titre, $chapo, $content, $idAuthor);
$blogpostRepository->enregistrer($post);

// for ($n = 0; $n < 100; $n++) {
//     $query->bindParam(':titre', $titre, PDO::PARAM_STR);
//     $query->bindParam(':chapo', $chapo, PDO::PARAM_STR);
//     $query->bindParam(':content', $content, PDO::PARAM_STR);
//     $query->bindParam(':idAuthor', $idAuthor, PDO::PARAM_INT);
//     $query->execute();
// }

//post categorie Astronomie
// $categorie = array(1);

// for ($n = 0; $n < 12; $n++) {
//     $query->bindParam(':titre', $titre, PDO::PARAM_STR);
//     $query->bindParam(':chapo', $chapo, PDO::PARAM_STR);
//     $query->bindParam(':content', $content, PDO::PARAM_STR);
//     $query->bindParam(':idAuthor', $idAuthor, PDO::PARAM_INT);
//     $query->execute();
//     $lastId = intval($pdo->lastInsertId());
//     $categorieRepository->associeCategorie($categorie, $lastId);
// }

//post categorie Informatique 
$categorie = array(2);
for ($n = 0; $n < 24; $n++) {
    $titre = $faker->sentence(rand(5, 10));
    $chapo = $faker->text(rand(75, 150));
    $content = $faker->text(rand(250, 500));
    $idAuthor = 1;
    $query->bindParam(':titre', $titre, PDO::PARAM_STR);
    $query->bindParam(':chapo', $chapo, PDO::PARAM_STR);
    $query->bindParam(':content', $content, PDO::PARAM_STR);
    $query->bindParam(':idAuthor', $idAuthor, PDO::PARAM_INT);
    $query->execute();
    $lastId = intval($pdo->lastInsertId());
    $categorieRepository->associeCategorie($categorie, $lastId);
}


$categorie = array(3);
for ($n = 0; $n < 50; $n++) {
    $query->bindParam(':titre', $titre, PDO::PARAM_STR);
    $query->bindParam(':chapo', $chapo, PDO::PARAM_STR);
    $query->bindParam(':content', $content, PDO::PARAM_STR);
    $query->bindParam(':idAuthor', $idAuthor, PDO::PARAM_INT);
    $query->execute();
    $lastId = intval($pdo->lastInsertId());
    $categorieRepository->associeCategorie($categorie, $lastId);
}



// create users
// $queryuser = $pdo->prepare('INSERT INTO `users` (pseudo, username, email, password)
//                                 VALUES (:pseudo, :username, :email, :password)');

// $password = password_hash('testUser', PASSWORD_BCRYPT);

// for ($n = 0; $n < 50; $n++) {

// }
