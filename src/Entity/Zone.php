<?php

namespace App\Entity;

use App\Repository\ZoneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ZoneRepository::class)]
#[ORM\Table(name: 'zones')]
class Zone
{
    const TYPE_STABULATION = 'stabulation';
    const TYPE_BOX_INDIVIDUEL = 'box_individuel';
    const TYPE_PARC = 'parc';
    const TYPE_NURSERY = 'nursery';
    const TYPE_ISOLEMENT = 'isolement';
    const TYPE_MATERNITE = 'maternite';
    const TYPE_AUTRE = 'autre';

    const STATUT_DISPONIBLE = 'disponible';
    const STATUT_OCCUPE = 'occupe';
    const STATUT_MAINTENANCE = 'maintenance';
    const STATUT_DESINFECTION = 'desinfection';

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

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 2, nullable: true)]
    private ?string $superficie = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $capaciteMaximale = null;

    #[ORM\Column(length: 50)]
    private ?string $statut = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $equipements = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $conditions = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\ManyToOne(targetEntity: Batiment::class, inversedBy: 'zones')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Batiment $batiment = null;

    #[ORM\OneToMany(targetEntity: Betail::class, mappedBy: 'zone')]
    private Collection $betails;

    public function __construct()
    {
        $this->betails = new ArrayCollection();
        $this->dateCreation = new \DateTime();
        $this->statut = self::STATUT_DISPONIBLE;
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

    public function getCapaciteMaximale(): ?int
    {
        return $this->capaciteMaximale;
    }

    public function setCapaciteMaximale(?int $capaciteMaximale): static
    {
        $this->capaciteMaximale = $capaciteMaximale;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;
        return $this;
    }

    public function getEquipements(): ?string
    {
        return $this->equipements;
    }

    public function setEquipements(?string $equipements): static
    {
        $this->equipements = $equipements;
        return $this;
    }

    public function getConditions(): ?string
    {
        return $this->conditions;
    }

    public function setConditions(?string $conditions): static
    {
        $this->conditions = $conditions;
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

    public function getBatiment(): ?Batiment
    {
        return $this->batiment;
    }

    public function setBatiment(?Batiment $batiment): static
    {
        $this->batiment = $batiment;
        return $this;
    }

    public function getBetails(): Collection
    {
        return $this->betails;
    }

    public function addBetail(Betail $betail): static
    {
        if (!$this->betails->contains($betail)) {
            $this->betails->add($betail);
            $betail->setZone($this);
        }
        return $this;
    }

    public function removeBetail(Betail $betail): static
    {
        if ($this->betails->removeElement($betail)) {
            if ($betail->getZone() === $this) {
                $betail->setZone(null);
            }
        }
        return $this;
    }

    public function getEffectifActuel(): int
    {
        return $this->betails->filter(function(Betail $betail) {
            return $betail->isActive();
        })->count();
    }

    public function getTauxOccupation(): float
    {
        if ($this->capaciteMaximale === null || $this->capaciteMaximale === 0) {
            return 0;
        }
        return ($this->getEffectifActuel() / $this->capaciteMaximale) * 100;
    }

    public function isDisponible(): bool
    {
        return $this->statut === self::STATUT_DISPONIBLE && 
               ($this->capaciteMaximale === null || $this->getEffectifActuel() < $this->capaciteMaximale);
    }

    public function getFerme(): ?Ferme
    {
        return $this->batiment?->getFerme();
    }

    public static function getTypesDisponibles(): array
    {
        return [
            self::TYPE_STABULATION => 'Stabulation libre',
            self::TYPE_BOX_INDIVIDUEL => 'Box individuel',
            self::TYPE_PARC => 'Parc',
            self::TYPE_NURSERY => 'Nursery',
            self::TYPE_ISOLEMENT => 'Isolement',
            self::TYPE_MATERNITE => 'Maternité',
            self::TYPE_AUTRE => 'Autre',
        ];
    }

    public static function getStatutsDisponibles(): array
    {
        return [
            self::STATUT_DISPONIBLE => 'Disponible',
            self::STATUT_OCCUPE => 'Occupé',
            self::STATUT_MAINTENANCE => 'En maintenance',
            self::STATUT_DESINFECTION => 'Désinfection',
        ];
    }

    public function __toString(): string
    {
        return $this->nom . ' (' . $this->numeroIdentification . ')';
    }
}