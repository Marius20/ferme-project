<?php

namespace App\Entity;

use App\Repository\VolailleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VolailleRepository::class)]
#[ORM\Table(name: 'volailles')]
class Volaille
{
    const TYPE_POULE_PONDEUSE = 'poule_pondeuse';
    const TYPE_POULE_CHAIR = 'poule_chair';
    const TYPE_PINTADE = 'pintade';
    const TYPE_CANARD = 'canard';
    const TYPE_DINDE = 'dinde';
    const TYPE_OIE = 'oie';

    const MODE_PONTE = 'ponte';
    const MODE_CHAIR = 'chair';

    const TYPE_SORTIE_VENTE = 'vente';
    const TYPE_SORTIE_MORTALITE = 'mortalite';
    const TYPE_SORTIE_ABATTAGE = 'abattage';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $race = null;

    #[ORM\Column(length: 50)]
    private ?string $mode = null;

    #[ORM\Column(length: 100)]
    private ?string $numeroLot = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $effectifInitial = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $effectif = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateEntree = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $ageEntree = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateSortie = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $typeSortie = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $effectifSortie = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 2, nullable: true)]
    private ?string $poidsUnitaireMoyen = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $prixUnitaire = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $acheteur = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\ManyToOne(targetEntity: Ferme::class, inversedBy: 'volailles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ferme $ferme = null;

    #[ORM\OneToMany(targetEntity: ProductionOeuf::class, mappedBy: 'volaille', orphanRemoval: true)]
    private Collection $productionsOeufs;

    #[ORM\OneToMany(targetEntity: AlimentationVolaille::class, mappedBy: 'volaille', orphanRemoval: true)]
    private Collection $alimentations;

    #[ORM\OneToMany(targetEntity: SoinVeterinaireVolaille::class, mappedBy: 'volaille', orphanRemoval: true)]
    private Collection $soinsVeterinaires;

    #[ORM\OneToMany(targetEntity: VenteVolaille::class, mappedBy: 'volaille')]
    private Collection $ventes;

    public function __construct()
    {
        $this->productionsOeufs = new ArrayCollection();
        $this->alimentations = new ArrayCollection();
        $this->soinsVeterinaires = new ArrayCollection();
        $this->ventes = new ArrayCollection();
        $this->dateCreation = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNumeroLot(): ?string
    {
        return $this->numeroLot;
    }

    public function setNumeroLot(string $numeroLot): static
    {
        $this->numeroLot = $numeroLot;
        return $this;
    }

    public function getEffectifInitial(): ?int
    {
        return $this->effectifInitial;
    }

    public function setEffectifInitial(int $effectifInitial): static
    {
        $this->effectifInitial = $effectifInitial;
        return $this;
    }

    public function getEffectif(): ?int
    {
        return $this->effectif;
    }

    public function setEffectif(int $effectif): static
    {
        $this->effectif = $effectif;
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

    public function getAgeEntree(): ?int
    {
        return $this->ageEntree;
    }

    public function setAgeEntree(?int $ageEntree): static
    {
        $this->ageEntree = $ageEntree;
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

    public function getEffectifSortie(): ?int
    {
        return $this->effectifSortie;
    }

    public function setEffectifSortie(?int $effectifSortie): static
    {
        $this->effectifSortie = $effectifSortie;
        return $this;
    }

    public function getPoidsUnitaireMoyen(): ?string
    {
        return $this->poidsUnitaireMoyen;
    }

    public function setPoidsUnitaireMoyen(?string $poidsUnitaireMoyen): static
    {
        $this->poidsUnitaireMoyen = $poidsUnitaireMoyen;
        return $this;
    }

    public function getPrixUnitaire(): ?string
    {
        return $this->prixUnitaire;
    }

    public function setPrixUnitaire(?string $prixUnitaire): static
    {
        $this->prixUnitaire = $prixUnitaire;
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

    public function getProductionsOeufs(): Collection
    {
        return $this->productionsOeufs;
    }

    public function addProductionsOeuf(ProductionOeuf $productionsOeuf): static
    {
        if (!$this->productionsOeufs->contains($productionsOeuf)) {
            $this->productionsOeufs->add($productionsOeuf);
            $productionsOeuf->setVolaille($this);
        }
        return $this;
    }

    public function removeProductionsOeuf(ProductionOeuf $productionsOeuf): static
    {
        if ($this->productionsOeufs->removeElement($productionsOeuf)) {
            if ($productionsOeuf->getVolaille() === $this) {
                $productionsOeuf->setVolaille(null);
            }
        }
        return $this;
    }

    public function getAlimentations(): Collection
    {
        return $this->alimentations;
    }

    public function addAlimentation(AlimentationVolaille $alimentation): static
    {
        if (!$this->alimentations->contains($alimentation)) {
            $this->alimentations->add($alimentation);
            $alimentation->setVolaille($this);
        }
        return $this;
    }

    public function removeAlimentation(AlimentationVolaille $alimentation): static
    {
        if ($this->alimentations->removeElement($alimentation)) {
            if ($alimentation->getVolaille() === $this) {
                $alimentation->setVolaille(null);
            }
        }
        return $this;
    }

    public function getSoinsVeterinaires(): Collection
    {
        return $this->soinsVeterinaires;
    }

    public function addSoinsVeterinaire(SoinVeterinaireVolaille $soinsVeterinaire): static
    {
        if (!$this->soinsVeterinaires->contains($soinsVeterinaire)) {
            $this->soinsVeterinaires->add($soinsVeterinaire);
            $soinsVeterinaire->setVolaille($this);
        }
        return $this;
    }

    public function removeSoinsVeterinaire(SoinVeterinaireVolaille $soinsVeterinaire): static
    {
        if ($this->soinsVeterinaires->removeElement($soinsVeterinaire)) {
            if ($soinsVeterinaire->getVolaille() === $this) {
                $soinsVeterinaire->setVolaille(null);
            }
        }
        return $this;
    }

    public function getVentes(): Collection
    {
        return $this->ventes;
    }

    public function addVente(VenteVolaille $vente): static
    {
        if (!$this->ventes->contains($vente)) {
            $this->ventes->add($vente);
            $vente->setVolaille($this);
        }
        return $this;
    }

    public function removeVente(VenteVolaille $vente): static
    {
        if ($this->ventes->removeElement($vente)) {
            if ($vente->getVolaille() === $this) {
                $vente->setVolaille(null);
            }
        }
        return $this;
    }

    public function isActive(): bool
    {
        return $this->effectif > 0;
    }

    public function isPondeuse(): bool
    {
        return $this->type === self::TYPE_POULE_PONDEUSE || $this->mode === self::MODE_PONTE;
    }

    public function getTauxMortalite(): float
    {
        $mortalite = $this->effectifInitial - $this->effectif;
        return ($mortalite / $this->effectifInitial) * 100;
    }

    public function getDureeElevage(): ?int
    {
        if ($this->dateSortie) {
            return $this->dateEntree->diff($this->dateSortie)->days;
        }
        return $this->dateEntree->diff(new \DateTime())->days;
    }

    public function getProductionOeufsJournaliere(): int
    {
        $aujourd_hui = new \DateTime();
        $productionJour = $this->productionsOeufs->filter(function(ProductionOeuf $production) use ($aujourd_hui) {
            return $production->getDate()->format('Y-m-d') === $aujourd_hui->format('Y-m-d');
        });

        $total = 0;
        foreach ($productionJour as $production) {
            $total += $production->getNombreOeufs();
        }
        return $total;
    }

    public static function getTypesDisponibles(): array
    {
        return [
            self::TYPE_POULE_PONDEUSE => 'Poule Pondeuse',
            self::TYPE_POULE_CHAIR => 'Poule de Chair',
            self::TYPE_PINTADE => 'Pintade',
            self::TYPE_CANARD => 'Canard',
            self::TYPE_DINDE => 'Dinde',
            self::TYPE_OIE => 'Oie',
        ];
    }

    public function __toString(): string
    {
        return $this->numeroLot . ' (' . $this->type . ' - ' . $this->effectif . ')';
    }
}