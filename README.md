# Projet 5 OpenClassrooms - Créez votre premier blog en PHP 

Projet 5 de mon parcours Développeur d'application PHP/Symfony chez OpenClassrooms. Création d'un Blog via une architecture MVC.


## Parcours Développeur d'application - PHP / Symfony

## Code Quality

La qualité du code a été validé par symfonyInsight.

## Description du projet
Voici les principales fonctionnalités disponibles suivant les différents statuts utilisateur:

Le visiteur:
<ul>
<li>Visiter la page d'accueil et ouvrir les différents liens disponibles (compte GitHub).</li>
<li>Envoyer un message au créateur du blog.</li>
<li>Parcourir la liste des blogs et parcourir la liste des qui lui sont associés.</li>
<li>Accès au CV.</li>
</ul>
L'utilisateur:
<ul>
<li>Prérequis: s'être enregistré via le formulaire d'inscription.</li>
<li>Accès aux mêmes fonctionnaités que le visiteur.</li>
<li>Ajout de commentaires.</li>

</ul>
Administrateur:
<ul>
<li>Prérequis: avoir le status administrateur.</li>
<li>Accès aux mêmes fonctionnalités que le visiteur.</li>
<li>Ajout/suppression/modification de blog post.</li>
<li>Validation/suppression de commentaires.</li>

</ul>

## Informations

Un thème de base a été choisi pour réaliser ce projet, il s'agit du thème Bootstrap Freelancer.

La version en ligne n'est pas encore disponible.

Vous pouvez directement vous identifier en tant qu'administrateur:

<ul>
<li>Identifiant: admin@exemple.com</li>
<li>Mot de passe: admin</li>
</ul>

## Prérequis
Php ainsi que Composer doivent être installés sur votre serveur afin de pouvoir correctement lancé le blog.

## Installation

**Etape 1 :** Cloner le Repositary sur votre serveur.

**Etape 2 :** Créer une base de données sur votre SGBD et importer le fichier blog.example.sql

**Etape 3 :** Remplir le fichier init.php avec les accès à votre BDD.

**Etape 4 :** Votre blog est désormais fonctionnel, vous pouvez utiliser l'accès administrateurou vou senregistrer en tant qu'utilisateur.

## Librairies utilisées
<ul>
<li>swiftmailer</li>
<li>Twig</li>
</ul>

### pour le développement
<ul>
 <li>filp/whoops</li>
 <li>"fakerphp/faker"</li>
 <li> "symfony/var-dumper"</li>
 <li>"symfony/mailer</li>
 <li>"symfony/google-mailer</li>
</ul>

## Auteur

Alexis Mathiot - Étudiant à Openclassrooms Parcours suivi Développeur d'application PHP/Symfony

