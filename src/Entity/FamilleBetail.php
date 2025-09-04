<?php

namespace App\Entity;

use App\Repository\FamilleBetailRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FamilleBetailRepository::class)]
#[ORM\Table(name: 'famille_betail')]
class FamilleBetail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, FamilleBetailFerme>
     */
    #[ORM\OneToMany(targetEntity: FamilleBetailFerme::class, mappedBy: 'famille', cascade: ['persist', 'remove'])]
    private Collection $familleFermes;

    public function __construct()
    {
        $this->familleFermes = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return Collection<int, FamilleBetailFerme>
     */
    public function getFamilleFermes(): Collection
    {
        return $this->familleFermes;
    }

    public function addFamilleFerme(FamilleBetailFerme $familleFerme): static
    {
        if (!$this->familleFermes->contains($familleFerme)) {
            $this->familleFermes->add($familleFerme);
            $familleFerme->setFamille($this);
        }

        return $this;
    }

    public function removeFamilleFerme(FamilleBetailFerme $familleFerme): static
    {
        if ($this->familleFermes->removeElement($familleFerme)) {
            if ($familleFerme->getFamille() === $this) {
                $familleFerme->setFamille(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->nom ?? '';
    }
}