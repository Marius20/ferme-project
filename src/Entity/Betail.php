<?php

namespace App\Entity;

use App\Repository\BetailRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BetailRepository::class)]
#[ORM\Table(name: 'betails')]
class Betail
{
    const TYPE_BOVIN = 'bovin';
    const TYPE_OVIN = 'ovin';
    const TYPE_CAPRIN = 'caprin';
    const TYPE_PORCIN = 'porcin';
    const TYPE_EQUIN = 'equin';

    const SOUS_TYPE_VACHE = 'vache';
    const SOUS_TYPE_BOEUF = 'boeuf';
    const SOUS_TYPE_TAUREAU = 'taureau';
    const SOUS_TYPE_MOUTON = 'mouton';
    const SOUS_TYPE_CHEVRE = 'chevre';
    const SOUS_TYPE_PORC = 'porc';
    const SOUS_TYPE_CHEVAL = 'cheval';
    const SOUS_TYPE_ANE = 'ane';

    const MODE_REPRODUCTION = 'reproduction';
    const MODE_ENGRAISSEMENT = 'engraissement';

    const SEXE_MALE = 'male';
    const SEXE_FEMELLE = 'femelle';

    const TYPE_SORTIE_VENTE = 'vente';
    const TYPE_SORTIE_MORTALITE = 'mortalite';
    const TYPE_SORTIE_ABATTAGE = 'abattage';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: TypeBetail::class, inversedBy: 'animaux')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeBetail $typeBetail = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $race = null;

    #[ORM\Column(length: 50)]
    private ?string $mode = null;

    #[ORM\Column(length: 100, unique: true)]
    private ?string $numeroIdentification = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $photo = null;

    #[ORM\Column(length: 10)]
    private ?string $sexe = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $age = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateEntree = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 2, nullable: true)]
    private ?string $poidsEntree = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateSortie = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $typeSortie = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 2, nullable: true)]
    private ?string $poidsSortie = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $prixVente = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $acheteur = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\ManyToOne(targetEntity: Ferme::class, inversedBy: 'betails')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ferme $ferme = null;

    #[ORM\ManyToOne(targetEntity: Zone::class, inversedBy: 'betails')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Zone $zone = null;

    #[ORM\OneToMany(targetEntity: Reproduction::class, mappedBy: 'betail', orphanRemoval: true)]
    private Collection $reproductions;

    #[ORM\OneToMany(targetEntity: Alimentation::class, mappedBy: 'betail', orphanRemoval: true)]
    private Collection $alimentations;

    #[ORM\OneToMany(targetEntity: SoinVeterinaire::class, mappedBy: 'betail', orphanRemoval: true)]
    private Collection $soinsVeterinaires;

    #[ORM\OneToMany(targetEntity: Vente::class, mappedBy: 'betail')]
    private Collection $ventes;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'enfantsMere')]
    #[ORM\JoinColumn(nullable: true)]
    private ?self $mere = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'enfantsPere')]
    #[ORM\JoinColumn(nullable: true)]
    private ?self $pere = null;

    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'mere')]
    private Collection $enfantsMere;

    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'pere')]
    private Collection $enfantsPere;

    public function __construct()
    {
        $this->reproductions = new ArrayCollection();
        $this->alimentations = new ArrayCollection();
        $this->soinsVeterinaires = new ArrayCollection();
        $this->ventes = new ArrayCollection();
        $this->enfantsMere = new ArrayCollection();
        $this->enfantsPere = new ArrayCollection();
        $this->dateCreation = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeBetail(): ?TypeBetail
    {
        return $this->typeBetail;
    }

    public function setTypeBetail(?TypeBetail $typeBetail): static
    {
        $this->typeBetail = $typeBetail;
        return $this;
    }

    /**
     * Raccourci pour obtenir le type de famille (bovin, ovin, etc.)
     */
    public function getTypeFamille(): ?string
    {
        return $this->typeBetail?->getFamilleBetail()?->getType();
    }

    /**
     * Raccourci pour obtenir le sous-type (vache, mouton, etc.)  
     */
    public function getSousType(): ?string
    {
        return $this->typeBetail?->getSousType();
    }

    /**
     * Méthode de compatibilité - retourne le type de famille
     */
    public function getType(): ?string
    {
        return $this->getTypeFamille();
    }

    public function getRace(): ?string
    {
        return $this->race;
    }

    public function setRace(?string $race): static
    {
        $this->race = $race;
        return $this;
    }

    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function setMode(string $mode): static
    {
        $this->mode = $mode;
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

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;
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

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): static
    {
        $this->age = $age;
        return $this;
    }

    public function getDateEntree(): ?\DateTimeInterface
    {
        return $this->dateEntree;
    }

    public function setDateEntree(\DateTimeInterface $dateEntree): static
    {
        $this->dateEntree = $dateEntree;
        return $this;
    }

    public function getPoidsEntree(): ?string
    {
        return $this->poidsEntree;
    }

    public function setPoidsEntree(?string $poidsEntree): static
    {
        $this->poidsEntree = $poidsEntree;
        return $this;
    }

    public function getDateSortie(): ?\DateTimeInterface
    {
        return $this->dateSortie;
    }

    public function setDateSortie(?\DateTimeInterface $dateSortie): static
    {
        $this->dateSortie = $dateSortie;
        return $this;
    }

    public function getTypeSortie(): ?string
    {
        return $this->typeSortie;
    }

    public function setTypeSortie(?string $typeSortie): static
    {
        $this->typeSortie = $typeSortie;
        return $this;
    }

    public function getPoidsSortie(): ?string
    {
        return $this->poidsSortie;
    }

    public function setPoidsSortie(?string $poidsSortie): static
    {
        $this->poidsSortie = $poidsSortie;
        return $this;
    }

    public function getPrixVente(): ?string
    {
        return $this->prixVente;
    }

    public function setPrixVente(?string $prixVente): static
    {
        $this->prixVente = $prixVente;
        return $this;
    }

    public function getAcheteur(): ?string
    {
        return $this->acheteur;
    }

    public function setAcheteur(?string $acheteur): static
    {
        $this->acheteur = $acheteur;
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

    public function getReproductions(): Collection
    {
        return $this->reproductions;
    }

    public function addReproduction(Reproduction $reproduction): static
    {
        if (!$this->reproductions->contains($reproduction)) {
            $this->reproductions->add($reproduction);
            $reproduction->setBetail($this);
        }
        return $this;
    }

    public function removeReproduction(Reproduction $reproduction): static
    {
        if ($this->reproductions->removeElement($reproduction)) {
            if ($reproduction->getBetail() === $this) {
                $reproduction->setBetail(null);
            }
        }
        return $this;
    }

    public function getAlimentations(): Collection
    {
        return $this->alimentations;
    }

    public function addAlimentation(Alimentation $alimentation): static
    {
        if (!$this->alimentations->contains($alimentation)) {
            $this->alimentations->add($alimentation);
            $alimentation->setBetail($this);
        }
        return $this;
    }

    public function removeAlimentation(Alimentation $alimentation): static
    {
        if ($this->alimentations->removeElement($alimentation)) {
            if ($alimentation->getBetail() === $this) {
                $alimentation->setBetail(null);
            }
        }
        return $this;
    }

    public function getSoinsVeterinaires(): Collection
    {
        return $this->soinsVeterinaires;
    }

    public function addSoinsVeterinaire(SoinVeterinaire $soinsVeterinaire): static
    {
        if (!$this->soinsVeterinaires->contains($soinsVeterinaire)) {
            $this->soinsVeterinaires->add($soinsVeterinaire);
            $soinsVeterinaire->setBetail($this);
        }
        return $this;
    }

    public function removeSoinsVeterinaire(SoinVeterinaire $soinsVeterinaire): static
    {
        if ($this->soinsVeterinaires->removeElement($soinsVeterinaire)) {
            if ($soinsVeterinaire->getBetail() === $this) {
                $soinsVeterinaire->setBetail(null);
            }
        }
        return $this;
    }

    public function getVentes(): Collection
    {
        return $this->ventes;
    }

    public function addVente(Vente $vente): static
    {
        if (!$this->ventes->contains($vente)) {
            $this->ventes->add($vente);
            $vente->setBetail($this);
        }
        return $this;
    }

    public function removeVente(Vente $vente): static
    {
        if ($this->ventes->removeElement($vente)) {
            if ($vente->getBetail() === $this) {
                $vente->setBetail(null);
            }
        }
        return $this;
    }

    public function isActive(): bool
    {
        return $this->dateSortie === null;
    }

    public function getGainPoids(): ?float
    {
        if ($this->poidsEntree && $this->poidsSortie) {
            return (float)$this->poidsSortie - (float)$this->poidsEntree;
        }
        return null;
    }

    public function getDureeElevage(): ?int
    {
        if ($this->dateSortie) {
            return $this->dateEntree->diff($this->dateSortie)->days;
        }
        return $this->dateEntree->diff(new \DateTime())->days;
    }

    public static function getTypesDisponibles(): array
    {
        return [
            self::TYPE_BOVIN => 'Bovin',
            self::TYPE_OVIN => 'Ovin',
            self::TYPE_CAPRIN => 'Caprin',
            self::TYPE_PORCIN => 'Porcin',
            self::TYPE_EQUIN => 'Équin',
        ];
    }

    public static function getSousTypesParType(string $type): array
    {
        return match($type) {
            self::TYPE_BOVIN => [
                self::SOUS_TYPE_VACHE => 'Vache',
                self::SOUS_TYPE_BOEUF => 'Bœuf',
                self::SOUS_TYPE_TAUREAU => 'Taureau',
            ],
            self::TYPE_OVIN => [
                self::SOUS_TYPE_MOUTON => 'Mouton',
            ],
            self::TYPE_CAPRIN => [
                self::SOUS_TYPE_CHEVRE => 'Chèvre',
            ],
            self::TYPE_PORCIN => [
                self::SOUS_TYPE_PORC => 'Porc',
            ],
            self::TYPE_EQUIN => [
                self::SOUS_TYPE_CHEVAL => 'Cheval',
                self::SOUS_TYPE_ANE => 'Âne',
            ],
            default => [],
        };
    }

    public function getZone(): ?Zone
    {
        return $this->zone;
    }

    public function setZone(?Zone $zone): static
    {
        $this->zone = $zone;
        return $this;
    }

    public function getBatiment(): ?Batiment
    {
        return $this->zone?->getBatiment();
    }

    public function getMere(): ?self
    {
        return $this->mere;
    }

    public function setMere(?self $mere): static
    {
        $this->mere = $mere;
        return $this;
    }

    public function getPere(): ?self
    {
        return $this->pere;
    }

    public function setPere(?self $pere): static
    {
        $this->pere = $pere;
        return $this;
    }

    public function getEnfantsMere(): Collection
    {
        return $this->enfantsMere;
    }

    public function addEnfantMere(self $enfant): static
    {
        if (!$this->enfantsMere->contains($enfant)) {
            $this->enfantsMere->add($enfant);
            $enfant->setMere($this);
        }
        return $this;
    }

    public function removeEnfantMere(self $enfant): static
    {
        if ($this->enfantsMere->removeElement($enfant)) {
            if ($enfant->getMere() === $this) {
                $enfant->setMere(null);
            }
        }
        return $this;
    }

    public function getEnfantsPere(): Collection
    {
        return $this->enfantsPere;
    }

    public function addEnfantPere(self $enfant): static
    {
        if (!$this->enfantsPere->contains($enfant)) {
            $this->enfantsPere->add($enfant);
            $enfant->setPere($this);
        }
        return $this;
    }

    public function removeEnfantPere(self $enfant): static
    {
        if ($this->enfantsPere->removeElement($enfant)) {
            if ($enfant->getPere() === $this) {
                $enfant->setPere(null);
            }
        }
        return $this;
    }

    public function getEnfants(): Collection
    {
        $enfants = new ArrayCollection();
        foreach ($this->enfantsMere as $enfant) {
            $enfants->add($enfant);
        }
        foreach ($this->enfantsPere as $enfant) {
            if (!$enfants->contains($enfant)) {
                $enfants->add($enfant);
            }
        }
        return $enfants;
    }

    public function isNeDansLaFerme(): bool
    {
        return $this->mere !== null || $this->pere !== null;
    }

    public function getParentsConsanguins(self $autre): bool
    {
        if (!$this->mere && !$this->pere && !$autre->mere && !$autre->pere) {
            return false;
        }
        
        return ($this->mere && ($this->mere === $autre->mere || $this->mere === $autre->pere)) ||
               ($this->pere && ($this->pere === $autre->mere || $this->pere === $autre->pere));
    }

    public function __toString(): string
    {
        $typeInfo = $this->typeBetail ? $this->typeBetail->getNom() : 'Type inconnu';
        return $this->numeroIdentification . ' (' . $typeInfo . ')';
    }
}