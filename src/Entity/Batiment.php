<?php

namespace App\Entity;

use App\Repository\BatimentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BatimentRepository::class)]
#[ORM\Table(name: 'batiments')]
class Batiment
{
    const TYPE_ETABLE = 'etable';
    const TYPE_ECURIE = 'ecurie';
    const TYPE_PORCHERIE = 'porcherie';
    const TYPE_BERGERIE = 'bergerie';
    const TYPE_CHEVRERIE = 'chevrerie';
    const TYPE_HANGAR = 'hangar';
    const TYPE_POULAILLER = 'poulailler';
    const TYPE_AUTRE = 'autre';

    const STATUT_ACTIF = 'actif';
    const STATUT_MAINTENANCE = 'maintenance';
    const STATUT_INACTIF = 'inactif';

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

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateConstruction = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateDerniereRenovation = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $equipements = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\ManyToOne(targetEntity: Ferme::class, inversedBy: 'batiments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ferme $ferme = null;

    #[ORM\ManyToOne(targetEntity: ZoneFerme::class, inversedBy: 'batiments')]
    #[ORM\JoinColumn(nullable: true)]
    private ?ZoneFerme $zoneFerme = null;

    #[ORM\OneToMany(targetEntity: Zone::class, mappedBy: 'batiment', orphanRemoval: true)]
    private Collection $zones;

    public function __construct()
    {
        $this->zones = new ArrayCollection();
        $this->dateCreation = new \DateTime();
        $this->statut = self::STATUT_ACTIF;
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

    public function getDateConstruction(): ?\DateTimeInterface
    {
        return $this->dateConstruction;
    }

    public function setDateConstruction(?\DateTimeInterface $dateConstruction): static
    {
        $this->dateConstruction = $dateConstruction;
        return $this;
    }

    public function getDateDerniereRenovation(): ?\DateTimeInterface
    {
        return $this->dateDerniereRenovation;
    }

    public function setDateDerniereRenovation(?\DateTimeInterface $dateDerniereRenovation): static
    {
        $this->dateDerniereRenovation = $dateDerniereRenovation;
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

    public function getZoneFerme(): ?ZoneFerme
    {
        return $this->zoneFerme;
    }

    public function setZoneFerme(?ZoneFerme $zoneFerme): static
    {
        $this->zoneFerme = $zoneFerme;
        return $this;
    }

    public function getZones(): Collection
    {
        return $this->zones;
    }

    public function addZone(Zone $zone): static
    {
        if (!$this->zones->contains($zone)) {
            $this->zones->add($zone);
            $zone->setBatiment($this);
        }
        return $this;
    }

    public function removeZone(Zone $zone): static
    {
        if ($this->zones->removeElement($zone)) {
            if ($zone->getBatiment() === $this) {
                $zone->setBatiment(null);
            }
        }
        return $this;
    }

    public function getNombreZones(): int
    {
        return $this->zones->count();
    }

    public function getOccupationActuelle(): int
    {
        $occupation = 0;
        foreach ($this->zones as $zone) {
            $occupation += $zone->getEffectifActuel();
        }
        return $occupation;
    }

    public function getTauxOccupation(): float
    {
        if ($this->capaciteMaximale === null || $this->capaciteMaximale === 0) {
            return 0;
        }
        return ($this->getOccupationActuelle() / $this->capaciteMaximale) * 100;
    }

    public static function getTypesDisponibles(): array
    {
        return [
            self::TYPE_ETABLE => 'Étable',
            self::TYPE_ECURIE => 'Écurie',
            self::TYPE_PORCHERIE => 'Porcherie',
            self::TYPE_BERGERIE => 'Bergerie',
            self::TYPE_CHEVRERIE => 'Chèvrerie',
            self::TYPE_HANGAR => 'Hangar',
            self::TYPE_POULAILLER => 'Poulailler',
            self::TYPE_AUTRE => 'Autre',
        ];
    }

    public static function getStatutsDisponibles(): array
    {
        return [
            self::STATUT_ACTIF => 'Actif',
            self::STATUT_MAINTENANCE => 'En maintenance',
            self::STATUT_INACTIF => 'Inactif',
        ];
    }

    public function __toString(): string
    {
        return $this->nom . ' (' . $this->numeroIdentification . ')';
    }
}