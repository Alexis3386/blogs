<?php

error_reporting(E_ALL);

require_once(dirname(dirname(__FILE__)) . '/init.php');
use App\Entity\Blogpost;

// use the factory to create a Faker\Generator instance
$faker = Faker\Factory::create();
// generate data by calling methods

function createPostCategorie(int $n, array $categorie, $faker, $blogpostRepository, $categorieRepository) {
    for ($i = 0; $i < $n; $i++) {
        $titre = $faker->sentence(rand(5, 10));
        $chapo = $faker->text(rand(75, 150));
        $content = $faker->text(rand(250, 500));
        $idAuthor = 1;
        $post = new Blogpost($titre, $chapo, $content, $idAuthor);
        $post = $blogpostRepository->enregistrer($post);
        $categorieRepository->associeCategorie($categorie, $post);
    }
}

// function createCommentaire(int $n) {
//     for ($i = 0; $i < $n; $i++) {
        
//     }
// }

//post categorie Informatique 
createPostCategorie(24, array(2), $faker, $blogpostRepository, $categorieRepository);

//post catégorie 
createPostCategorie(50, array(3), $faker, $blogpostRepository, $categorieRepository);

// create users
// $queryuser = $pdo->prepare('INSERT INTO `users` (pseudo, username, email, password)
//                                 VALUES (:pseudo, :username, :email, :password)');

// $password = password_hash('testUser', PASSWORD_BCRYPT);

// for ($n = 0; $n < 50; $n++) {

// }
