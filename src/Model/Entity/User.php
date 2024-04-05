<?php


namespace App\Model\Entity;


class User
{


    public function __construct(
        private readonly int    $id,
        private readonly string $email,
        private readonly string $pseudo,
        private readonly string $username,
        private readonly bool   $isadmin,
    )
    {
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function getisadmin(): bool
    {
        return $this->isadmin;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPseudo(): string
    {
        return $this->pseudo;
    }

    /**
     * @return String
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}
