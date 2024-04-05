<?php

namespace App\Model\Entity;

use DateTime;

class Blogpost
{
    public function __construct(
        private string $titre,
        private String $chapo,
        private string $content,
        private int $authorId,
        private ?string $slug = null,
        private ?int $id = null,
        private ?DateTime $datecreation = null,
        private ?DateTime $datemodification = null
    ) {
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function setDateCreation(DateTime $datecreation): void
    {
        $this->datecreation = $datecreation;
    }

    public function setDateModification(DateTime $datemodification): void
    {
        $this->datemodification = $datemodification;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setTitre(string $titre): void
    {
        $this->titre = $titre;
    }

    public function setChapo(string $chapo): void
    {
        $this->chapo = $chapo;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
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

    public function getSlug(): ?string
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
