<?php
// src/Entity/Author.php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
class Author
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column]
    private ?int $nb_books = 0;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }
    public function getNbBook(): ?int
{
    return $this->nb_books;
}

public function setNbBook(int $nb_books): static
{
    $this->nb_books = $nb_books;
    return $this;
}

 

public function getnb_books(): ?int
{
    return $this->nb_books;
}

public function setnb_books(int $nb_books): self
{
    $this->nb_books = $nb_books;
    return $this;
}
  #[ORM\OneToMany(mappedBy: 'author', targetEntity: Book::class)]
private Collection $books;
  
}