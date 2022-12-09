<?php

error_reporting(E_ALL);

require_once(dirname(dirname(__FILE__)) . '/init.php');

use App\Entity\Blogpost;
use App\Entity\Commentaire;

// use the factory to create a Faker\Generator instance
$faker = Faker\Factory::create();
// generate data by calling methods

function createPostCategorie(int $n, array $categorie, $faker, $blogpostRepository, $categorieRepository)
{
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

function createUser(int $n, $faker, $userRepository)
{
    for ($i = 0; $i < $n; $i++) {
        $username = $faker->name();
        $email = $faker->email();
        $pseudo = $faker->firstname();
        $password = '123456Ab';
        $userRepository->enregistrer($password, $pseudo, $username, $email);
    }
}

function createComment(int $n, $idUser, $idPost, $faker, $commentaireRepository)
{
    for ($i = 0; $i < $n; $i++) {
        $content = $faker->text(rand(75, 150));
        $commentaire = new Commentaire($content, $idPost, $idUser, true);
        $commentaireRepository->enregistrer($commentaire);
    }
}


//post categorie Informatique 
// createPostCategorie(24, array(2), $faker, $blogpostRepository, $categorieRepository);

//post catégorie Jeux vidéo
// createPostCategorie(50, array(3), $faker, $blogpostRepository, $categorieRepository);

//post catégorie Jeux vidéo et Sport
// createPostCategorie(25, array(3, 4), $faker, $blogpostRepository, $categorieRepository);

// createUser(15, $faker, $userRepository);

createComment(10, 3, 3, $faker, $commentaireRepository);
