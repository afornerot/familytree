<?php

namespace App\Entity;

use App\Repository\MariageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MariageRepository::class)]
class Mariage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Individu::class, inversedBy: 'mariagesIndividu1')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Individu $individu1 = null;

    #[ORM\ManyToOne(targetEntity: Individu::class, inversedBy: 'mariagesIndividu2')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Individu $individu2 = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateMariage = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lieuMariage = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIndividu1(): ?Individu
    {
        return $this->individu1;
    }

    public function setIndividu1(?Individu $individu1): static
    {
        $this->individu1 = $individu1;

        return $this;
    }

    public function getIndividu2(): ?Individu
    {
        return $this->individu2;
    }

    public function setIndividu2(?Individu $individu2): static
    {
        $this->individu2 = $individu2;

        return $this;
    }

    public function getDateMariage(): ?\DateTimeInterface
    {
        return $this->dateMariage;
    }

    public function setDateMariage(?\DateTimeInterface $dateMariage): static
    {
        $this->dateMariage = $dateMariage;

        return $this;
    }

    public function getLieuMariage(): ?string
    {
        return $this->lieuMariage;
    }

    public function setLieuMariage(?string $lieuMariage): static
    {
        $this->lieuMariage = $lieuMariage;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }
}
