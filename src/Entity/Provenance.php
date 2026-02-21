<?php

namespace App\Entity;

use App\Repository\ProvenanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProvenanceRepository::class)]
class Provenance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $pays = null;

    /**
     * @var Collection<int, Cafe>
     */
    #[ORM\OneToMany(targetEntity: Cafe::class, mappedBy: 'provenance')]
    private Collection $cafes;

    public function __construct()
    {
        $this->cafes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): static
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * @return Collection<int, Cafe>
     */
    public function getCafes(): Collection
    {
        return $this->cafes;
    }

    public function addCafe(Cafe $cafe): static
    {
        if (!$this->cafes->contains($cafe)) {
            $this->cafes->add($cafe);
            $cafe->setProvenance($this);
        }

        return $this;
    }

    public function removeCafe(Cafe $cafe): static
    {
        if ($this->cafes->removeElement($cafe)) {
            // set the owning side to null (unless already changed)
            if ($cafe->getProvenance() === $this) {
                $cafe->setProvenance(null);
            }
        }

        return $this;
    }
}
