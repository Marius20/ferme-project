<?php

namespace App\Entity;

use App\Repository\FamilleBetailFermeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FamilleBetailFermeRepository::class)]
#[ORM\Table(name: 'famille_betail_ferme')]
class FamilleBetailFerme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Ferme::class, inversedBy: 'famillesBetailFerme')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ferme $ferme = null;

    #[ORM\ManyToOne(targetEntity: FamilleBetail::class, inversedBy: 'familleFermes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FamilleBetail $famille = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $descriptifPersonnalise = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateAjout = null;

    #[ORM\Column]
    private bool $actif = true;

    /**
     * @var Collection<int, TypeBetail>
     */
    #[ORM\OneToMany(targetEntity: TypeBetail::class, mappedBy: 'familleFerme', cascade: ['persist', 'remove'])]
    private Collection $typesBetail;

    public function __construct()
    {
        $this->typesBetail = new ArrayCollection();
        $this->dateAjout = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFerme(): ?Ferme
    {
        return $this->ferme;
    }

    public function setFerme(?Ferme $ferme): static
    {
        $this->ferme = $ferme;
        return $this;
    }

    public function getFamille(): ?FamilleBetail
    {
        return $this->famille;
    }

    public function setFamille(?FamilleBetail $famille): static
    {
        $this->famille = $famille;
        return $this;
    }

    public function getDescriptifPersonnalise(): ?string
    {
        return $this->descriptifPersonnalise;
    }

    public function setDescriptifPersonnalise(?string $descriptifPersonnalise): static
    {
        $this->descriptifPersonnalise = $descriptifPersonnalise;
        return $this;
    }

    public function getDateAjout(): ?\DateTimeImmutable
    {
        return $this->dateAjout;
    }

    public function setDateAjout(\DateTimeImmutable $dateAjout): static
    {
        $this->dateAjout = $dateAjout;
        return $this;
    }

    public function isActif(): bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): static
    {
        $this->actif = $actif;
        return $this;
    }

    /**
     * @return Collection<int, TypeBetail>
     */
    public function getTypesBetail(): Collection
    {
        return $this->typesBetail;
    }

    public function addTypesBetail(TypeBetail $typesBetail): static
    {
        if (!$this->typesBetail->contains($typesBetail)) {
            $this->typesBetail->add($typesBetail);
            $typesBetail->setFamilleFerme($this);
        }

        return $this;
    }

    public function removeTypesBetail(TypeBetail $typesBetail): static
    {
        if ($this->typesBetail->removeElement($typesBetail)) {
            if ($typesBetail->getFamilleFerme() === $this) {
                $typesBetail->setFamilleFerme(null);
            }
        }

        return $this;
    }

    /**
     * Retourne l'effectif total de cette famille pour cette ferme
     */
    public function getEffectifTotal(): int
    {
        return $this->typesBetail->reduce(
            fn(int $total, TypeBetail $type) => $total + $type->getEffectif(),
            0
        );
    }

    /**
     * Retourne la description à utiliser (personnalisée ou celle de la famille)
     */
    public function getDescriptionEffective(): ?string
    {
        return $this->descriptifPersonnalise ?: $this->famille?->getDescription();
    }

    public function __toString(): string
    {
        return sprintf('%s - %s', $this->famille?->getNom() ?? 'Famille', $this->ferme?->getNom() ?? 'Ferme');
    }
}