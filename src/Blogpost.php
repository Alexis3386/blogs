<?php

namespace App;

use DateTime;

class Blogpost
{
    /**
     * @param Categorie[] $categorie
     */
    public function __construct(private int $id, private string $titre, private String $chapo, private string $content,
    private array $images, private DateTime $datecreation, private ?DateTime $datemodification, private array $categorie)
    {

    }

    public function getID(): int {
        return $this->id;
    }

    public function getTitre() : String {
        return $this->titre;
    }

    public function getChapo() : String {
        return $this->chapo;
    }

    public function getContent() : String {
        return $this->content;
    }

    public function getImages() : array {
        return $this->image;
    }

    public function getDatecreation() : DateTime {
        return $this->datecreation;
    }

    public function getCategorie() : array {
        return $this->categorie;
    }
}