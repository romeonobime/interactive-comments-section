<?php

namespace App\Entity;

use App\Repository\ImagesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImagesRepository::class)]
class Images
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $png = null;

    #[ORM\Column(length: 255)]
    private ?string $webp = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPng(): ?string
    {
        return $this->png;
    }

    public function setPng(string $png): static
    {
        $this->png = $png;

        return $this;
    }

    public function getWebp(): ?string
    {
        return $this->webp;
    }

    public function setWebp(string $webp): static
    {
        $this->webp = $webp;

        return $this;
    }
}
