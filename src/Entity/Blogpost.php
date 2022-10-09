<?php

namespace App\Entity;

use DateTime;

class Blogpost
{
    /**
     * @param Categorie[] $categorie
     */
    public function __construct(
        private ?int $id,
        private string $titre,
        private string $slug,
        private String $chapo,
        private string $content,
        private int $authorId,
        private DateTime $datecreation,
        private DateTime $datemodification
    ) {
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setSlug(string $slug)
    {
        $this->slug = $slug;
    }

    public function setDateCreation(DateTime $datecreation)
    {
        $this->datecreation = $datecreation;
    }

    public function setDateModification(DateTime $datemodification)
    {
        $this->datemodification = $datemodification;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitre(): string
    {
        return $this->titre;
    }

    public function getChapo(): string
    {
        return $this->chapo;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    public function getImages(): int
    {
        return $this->image;
    }

    public function getSlug(): int
    {
        return $this->slug;
    }

    public function getDateCreation(): DateTime
    {
        return $this->datecreation;
    }

    public function getDateModification(): DateTime
    {
        return $this->datemodification;
    }
}
