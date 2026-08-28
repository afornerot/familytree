<?php

namespace App\Entity;

use App\Repository\IndividuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: IndividuRepository::class)]
class Individu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom de naissance est obligatoire.')]
    private ?string $nomNaissance = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prenom1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prenom2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prenom3 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lieuNaissance = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateNaissance = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateDeces = null;

    #[ORM\Column(length: 1)]
    #[Assert\Choice(choices: ['M', 'F'], message: 'Le sexe doit être M ou F.')]
    private ?string $sexe = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $photo = null;

    #[ORM\ManyToOne(targetEntity: Individu::class, inversedBy: 'enfantsPere')]
    private ?Individu $pere = null;

    #[ORM\ManyToOne(targetEntity: Individu::class, inversedBy: 'enfantsMere')]
    private ?Individu $mere = null;

    #[ORM\OneToMany(targetEntity: Individu::class, mappedBy: 'pere')]
    private Collection $enfantsPere;

    #[ORM\OneToMany(targetEntity: Individu::class, mappedBy: 'mere')]
    private Collection $enfantsMere;

    #[ORM\OneToMany(targetEntity: Mariage::class, mappedBy: 'individu1', orphanRemoval: true)]
    private Collection $mariagesIndividu1;

    #[ORM\OneToMany(targetEntity: Mariage::class, mappedBy: 'individu2', orphanRemoval: true)]
    private Collection $mariagesIndividu2;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->enfantsPere = new ArrayCollection();
        $this->enfantsMere = new ArrayCollection();
        $this->mariagesIndividu1 = new ArrayCollection();
        $this->mariagesIndividu2 = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomNaissance(): ?string
    {
        return $this->nomNaissance;
    }

    public function setNomNaissance(string $nomNaissance): static
    {
        $this->nomNaissance = $nomNaissance;

        return $this;
    }

    public function getPrenom1(): ?string
    {
        return $this->prenom1;
    }

    public function setPrenom1(?string $prenom1): static
    {
        $this->prenom1 = $prenom1;

        return $this;
    }

    public function getPrenom2(): ?string
    {
        return $this->prenom2;
    }

    public function setPrenom2(?string $prenom2): static
    {
        $this->prenom2 = $prenom2;

        return $this;
    }

    public function getPrenom3(): ?string
    {
        return $this->prenom3;
    }

    public function setPrenom3(?string $prenom3): static
    {
        $this->prenom3 = $prenom3;

        return $this;
    }

    public function getLieuNaissance(): ?string
    {
        return $this->lieuNaissance;
    }

    public function setLieuNaissance(?string $lieuNaissance): static
    {
        $this->lieuNaissance = $lieuNaissance;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(?\DateTimeInterface $dateNaissance): static
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getDateDeces(): ?\DateTimeInterface
    {
        return $this->dateDeces;
    }

    public function setDateDeces(?\DateTimeInterface $dateDeces): static
    {
        $this->dateDeces = $dateDeces;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): static
    {
        $this->sexe = $sexe;

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

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    public function getPere(): ?Individu
    {
        return $this->pere;
    }

    public function setPere(?Individu $pere): static
    {
        $this->pere = $pere;

        return $this;
    }

    public function getMere(): ?Individu
    {
        return $this->mere;
    }

    public function setMere(?Individu $mere): static
    {
        $this->mere = $mere;

        return $this;
    }

    /**
     * @return Collection<int, Individu>
     */
    public function getEnfantsPere(): Collection
    {
        return $this->enfantsPere;
    }

    public function addEnfantPere(Individu $enfantPere): static
    {
        if (!$this->enfantsPere->contains($enfantPere)) {
            $this->enfantsPere->add($enfantPere);
            $enfantPere->setPere($this);
        }

        return $this;
    }

    public function removeEnfantPere(Individu $enfantPere): static
    {
        if ($this->enfantsPere->removeElement($enfantPere)) {
            if ($enfantPere->getPere() === $this) {
                $enfantPere->setPere(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Individu>
     */
    public function getEnfantsMere(): Collection
    {
        return $this->enfantsMere;
    }

    public function addEnfantMere(Individu $enfantMere): static
    {
        if (!$this->enfantsMere->contains($enfantMere)) {
            $this->enfantsMere->add($enfantMere);
            $enfantMere->setMere($this);
        }

        return $this;
    }

    public function removeEnfantMere(Individu $enfantMere): static
    {
        if ($this->enfantsMere->removeElement($enfantMere)) {
            if ($enfantMere->getMere() === $this) {
                $enfantMere->setMere(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Mariage>
     */
    public function getMariagesIndividu1(): Collection
    {
        return $this->mariagesIndividu1;
    }

    public function addMariageIndividu1(Mariage $mariageIndividu1): static
    {
        if (!$this->mariagesIndividu1->contains($mariageIndividu1)) {
            $this->mariagesIndividu1->add($mariageIndividu1);
            $mariageIndividu1->setIndividu1($this);
        }

        return $this;
    }

    public function removeMariageIndividu1(Mariage $mariageIndividu1): static
    {
        if ($this->mariagesIndividu1->removeElement($mariageIndividu1)) {
            if ($mariageIndividu1->getIndividu1() === $this) {
                $mariageIndividu1->setIndividu1(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Mariage>
     */
    public function getMariagesIndividu2(): Collection
    {
        return $this->mariagesIndividu2;
    }

    public function addMariageIndividu2(Mariage $mariageIndividu2): static
    {
        if (!$this->mariagesIndividu2->contains($mariageIndividu2)) {
            $this->mariagesIndividu2->add($mariageIndividu2);
            $mariageIndividu2->setIndividu2($this);
        }

        return $this;
    }

    public function removeMariageIndividu2(Mariage $mariageIndividu2): static
    {
        if ($this->mariagesIndividu2->removeElement($mariageIndividu2)) {
            if ($mariageIndividu2->getIndividu2() === $this) {
                $mariageIndividu2->setIndividu2(null);
            }
        }

        return $this;
    }

    public function getNomComplet(): string
    {
        $prenoms = array_filter([$this->prenom1, $this->prenom2, $this->prenom3]);
        $prenomsStr = implode(' ', $prenoms);

        return trim($prenomsStr . ' ' . $this->nomNaissance);
    }

    public function getNomPrenom(): string
    {
        return $this->nomNaissance . ' ' . $this->getNomComplet();
    }

    /**
     * @return Collection<int, Mariage>
     */
    public function getTousLesMariages(): Collection
    {
        $all = new ArrayCollection();
        foreach ($this->mariagesIndividu1 as $m) {
            $all->add($m);
        }
        foreach ($this->mariagesIndividu2 as $m) {
            if (!$all->contains($m)) {
                $all->add($m);
            }
        }

        return $all;
    }

    public function getConjoint(Mariage $mariage): ?Individu
    {
        if ($mariage->getIndividu1() === $this) {
            return $mariage->getIndividu2();
        }

        return $mariage->getIndividu1();
    }

    /**
     * @return Collection<int, Individu>
     */
    public function getTousLesEnfants(): Collection
    {
        $all = new ArrayCollection();
        foreach ($this->enfantsPere as $enfant) {
            $all->add($enfant);
        }
        foreach ($this->enfantsMere as $enfant) {
            if (!$all->contains($enfant)) {
                $all->add($enfant);
            }
        }

        return $all;
    }

    public function isDecede(): bool
    {
        return $this->dateDeces !== null;
    }

    public function isOrphelin(): bool
    {
        return $this->pere === null && $this->mere === null;
    }

    public function __toString(): string
    {
        return $this->getNomComplet();
    }
}
