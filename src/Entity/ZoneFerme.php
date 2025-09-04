<?php

namespace App\Entity;

use App\Repository\ZoneFermeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ZoneFermeRepository::class)]
#[ORM\Table(name: 'zones_fermes')]
class ZoneFerme
{
    const TYPE_PRODUCTION = 'production';
    const TYPE_STOCKAGE = 'stockage';
    const TYPE_ADMINISTRATION = 'administration';
    const TYPE_PARCOURS = 'parcours';
    const TYPE_CULTURES = 'cultures';
    const TYPE_AUTRE = 'autre';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 100, unique: true)]
    private ?string $numeroIdentification = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $superficie = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $localisation = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $caracteristiques = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $acces = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column]
    private bool $actif = true;

    #[ORM\ManyToOne(targetEntity: Ferme::class, inversedBy: 'zonesFermes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ferme $ferme = null;

    #[ORM\OneToMany(targetEntity: Batiment::class, mappedBy: 'zoneFerme')]
    private Collection $batiments;

    public function __construct()
    {
        $this->batiments = new ArrayCollection();
        $this->dateCreation = new \DateTime();
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

    public function getNumeroIdentification(): ?string
    {
        return $this->numeroIdentification;
    }

    public function setNumeroIdentification(string $numeroIdentification): static
    {
        $this->numeroIdentification = $numeroIdentification;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;
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

    public function getSuperficie(): ?string
    {
        return $this->superficie;
    }

    public function setSuperficie(?string $superficie): static
    {
        $this->superficie = $superficie;
        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(?string $localisation): static
    {
        $this->localisation = $localisation;
        return $this;
    }

    public function getCaracteristiques(): ?string
    {
        return $this->caracteristiques;
    }

    public function setCaracteristiques(?string $caracteristiques): static
    {
        $this->caracteristiques = $caracteristiques;
        return $this;
    }

    public function getAcces(): ?string
    {
        return $this->acces;
    }

    public function setAcces(?string $acces): static
    {
        $this->acces = $acces;
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

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): static
    {
        $this->dateCreation = $dateCreation;
        return $this;
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

    public function getBatiments(): Collection
    {
        return $this->batiments;
    }

    public function addBatiment(Batiment $batiment): static
    {
        if (!$this->batiments->contains($batiment)) {
            $this->batiments->add($batiment);
            $batiment->setZoneFerme($this);
        }
        return $this;
    }

    public function removeBatiment(Batiment $batiment): static
    {
        if ($this->batiments->removeElement($batiment)) {
            if ($batiment->getZoneFerme() === $this) {
                $batiment->setZoneFerme(null);
            }
        }
        return $this;
    }

    public function getNombreBatiments(): int
    {
        return $this->batiments->count();
    }

    public function getSuperficieTotaleBatiments(): string
    {
        $total = '0';
        foreach ($this->batiments as $batiment) {
            if ($batiment->getSuperficie()) {
                $total = bcadd($total, $batiment->getSuperficie(), 2);
            }
        }
        return $total;
    }

    public function getCapaciteTotale(): int
    {
        $capacite = 0;
        foreach ($this->batiments as $batiment) {
            if ($batiment->getCapaciteMaximale()) {
                $capacite += $batiment->getCapaciteMaximale();
            }
        }
        return $capacite;
    }

    public static function getTypesDisponibles(): array
    {
        return [
            self::TYPE_PRODUCTION => 'Zone de production',
            self::TYPE_STOCKAGE => 'Zone de stockage',
            self::TYPE_ADMINISTRATION => 'Zone administrative',
            self::TYPE_PARCOURS => 'Zone de parcours',
            self::TYPE_CULTURES => 'Zone de cultures',
            self::TYPE_AUTRE => 'Autre',
        ];
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

    public function __toString(): string
    {
        return $this->nom . ' (' . $this->numeroIdentification . ')';
    }
}