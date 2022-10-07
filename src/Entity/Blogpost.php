<?php

namespace App\Entity;

use DateTime;

class Blogpost
{
    /**
     * @param Categorie[] $categorie
     */
    public function __construct(
        private int $id,
        private string $titre,
        private String $chapo,
        private string $content,
        private int $authorId,
        private DateTime $datecreation,
        private ?DateTime $datemodification
    ) {
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

    public function getDatecreation(): DateTime
    {
        return $this->datecreation;
    }
}
