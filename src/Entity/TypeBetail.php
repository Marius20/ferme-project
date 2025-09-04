<?php

namespace App\Entity;

use App\Repository\TypeBetailRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeBetailRepository::class)]
#[ORM\Table(name: 'type_betail')]
class TypeBetail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column]
    private int $effectif = 0;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateCreation = null;

    #[ORM\Column]
    private bool $actif = true;

    #[ORM\ManyToOne(targetEntity: FamilleBetailFerme::class, inversedBy: 'typesBetail')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FamilleBetailFerme $familleFerme = null;

    /**
     * @var Collection<int, Betail>
     */
    #[ORM\OneToMany(targetEntity: Betail::class, mappedBy: 'typeBetail', cascade: ['persist'])]
    private Collection $animaux;

    public function __construct()
    {
        $this->animaux = new ArrayCollection();
        $this->dateCreation = new \DateTimeImmutable();
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

    public function getEffectif(): int
    {
        return $this->effectif;
    }

    public function setEffectif(int $effectif): static
    {
        $this->effectif = $effectif;
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

    public function getDateCreation(): ?\DateTimeImmutable
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeImmutable $dateCreation): static
    {
        $this->dateCreation = $dateCreation;
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

    public function getFamilleFerme(): ?FamilleBetailFerme
    {
        return $this->familleFerme;
    }

    public function setFamilleFerme(?FamilleBetailFerme $familleFerme): static
    {
        $this->familleFerme = $familleFerme;
        return $this;
    }

    /**
     * @return Collection<int, Betail>
     */
    public function getAnimaux(): Collection
    {
        return $this->animaux;
    }

    public function addAnimaux(Betail $animaux): static
    {
        if (!$this->animaux->contains($animaux)) {
            $this->animaux->add($animaux);
            $animaux->setTypeBetail($this);
        }

        return $this;
    }

    public function removeAnimaux(Betail $animaux): static
    {
        if ($this->animaux->removeElement($animaux)) {
            if ($animaux->getTypeBetail() === $this) {
                $animaux->setTypeBetail(null);
            }
        }

        return $this;
    }

    /**
     * Retourne le nombre d'animaux actifs de ce type
     */
    public function getNombreAnimauxActifs(): int
    {
        return $this->animaux->filter(fn(Betail $betail) => $betail->isActive())->count();
    }

    /**
     * Sous-types prédéfinis par nom de famille
     */
    public static function getTypesParFamille(string $nomFamille): array
    {
        return match(strtolower($nomFamille)) {
            'bovins' => [
                'vache' => 'Vache',
                'taureau' => 'Taureau',
                'boeuf' => 'Bœuf',
                'genisse' => 'Génisse',
                'veau' => 'Veau',
            ],
            'ovins' => [
                'mouton' => 'Mouton',
                'brebis' => 'Brebis',
                'belier' => 'Bélier',
                'agneau' => 'Agneau',
            ],
            'caprins' => [
                'chevre' => 'Chèvre',
                'bouc' => 'Bouc',
                'chevreau' => 'Chevreau',
            ],
            'porcins' => [
                'porc' => 'Porc',
                'truie' => 'Truie',
                'verrat' => 'Verrat',
                'porcelet' => 'Porcelet',
            ],
            'équins' => [
                'cheval' => 'Cheval',
                'jument' => 'Jument',
                'ane' => 'Âne',
                'mule' => 'Mule',
                'poulain' => 'Poulain',
            ],
            'volailles' => [
                'poule' => 'Poule',
                'coq' => 'Coq',
                'pintade' => 'Pintade',
                'canard' => 'Canard',
                'oie' => 'Oie',
            ],
            default => []
        };
    }

    public function __toString(): string
    {
        return $this->nom ?? '';
    }
}