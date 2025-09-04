<?php

namespace App\Entity;

use App\Repository\StockRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StockRepository::class)]
#[ORM\Table(name: 'stocks')]
class Stock
{
    const TYPE_FOIN = 'foin';
    const TYPE_MAIS = 'mais';
    const TYPE_SON = 'son';
    const TYPE_TOURTEAU = 'tourteau';
    const TYPE_PROVENDE = 'provende';
    const TYPE_CONCENTRE = 'concentre';
    const TYPE_SUPPLEMENT = 'supplement';
    const TYPE_MEDICAMENT = 'medicament';

    const UNITE_KG = 'kg';
    const UNITE_TONNE = 'tonne';
    const UNITE_LITRE = 'litre';
    const UNITE_SAC = 'sac';
    const UNITE_BOTTE = 'botte';

    const SOURCE_ACHAT = 'achat';
    const SOURCE_PRODUCTION = 'production';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 20)]
    private ?string $unite = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 3)]
    private ?string $quantiteActuelle = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 3)]
    private ?string $quantiteMinimum = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 3, nullable: true)]
    private ?string $quantiteMaximum = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $prixUnitaireMoyen = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2, nullable: true)]
    private ?string $valeurStock = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $emplacement = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $datePeremption = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateModification = null;

    #[ORM\ManyToOne(targetEntity: Ferme::class, inversedBy: 'stocks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ferme $ferme = null;

    #[ORM\OneToMany(targetEntity: MouvementStock::class, mappedBy: 'stock', orphanRemoval: true)]
    private Collection $mouvements;

    #[ORM\OneToMany(targetEntity: Alimentation::class, mappedBy: 'stock')]
    private Collection $alimentations;

    #[ORM\OneToMany(targetEntity: AlimentationVolaille::class, mappedBy: 'stock')]
    private Collection $alimentationsVolaille;

    public function __construct()
    {
        $this->mouvements = new ArrayCollection();
        $this->alimentations = new ArrayCollection();
        $this->alimentationsVolaille = new ArrayCollection();
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

    public function getUnite(): ?string
    {
        return $this->unite;
    }

    public function setUnite(string $unite): static
    {
        $this->unite = $unite;
        return $this;
    }

    public function getQuantiteActuelle(): ?string
    {
        return $this->quantiteActuelle;
    }

    public function setQuantiteActuelle(string $quantiteActuelle): static
    {
        $this->quantiteActuelle = $quantiteActuelle;
        return $this;
    }

    public function getQuantiteMinimum(): ?string
    {
        return $this->quantiteMinimum;
    }

    public function setQuantiteMinimum(string $quantiteMinimum): static
    {
        $this->quantiteMinimum = $quantiteMinimum;
        return $this;
    }

    public function getQuantiteMaximum(): ?string
    {
        return $this->quantiteMaximum;
    }

    public function setQuantiteMaximum(?string $quantiteMaximum): static
    {
        $this->quantiteMaximum = $quantiteMaximum;
        return $this;
    }

    public function getPrixUnitaireMoyen(): ?string
    {
        return $this->prixUnitaireMoyen;
    }

    public function setPrixUnitaireMoyen(?string $prixUnitaireMoyen): static
    {
        $this->prixUnitaireMoyen = $prixUnitaireMoyen;
        return $this;
    }

    public function getValeurStock(): ?string
    {
        return $this->valeurStock;
    }

    public function setValeurStock(?string $valeurStock): static
    {
        $this->valeurStock = $valeurStock;
        return $this;
    }

    public function getEmplacement(): ?string
    {
        return $this->emplacement;
    }

    public function setEmplacement(?string $emplacement): static
    {
        $this->emplacement = $emplacement;
        return $this;
    }

    public function getDatePeremption(): ?\DateTimeInterface
    {
        return $this->datePeremption;
    }

    public function setDatePeremption(?\DateTimeInterface $datePeremption): static
    {
        $this->datePeremption = $datePeremption;
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

    public function getDateModification(): ?\DateTimeInterface
    {
        return $this->dateModification;
    }

    public function setDateModification(?\DateTimeInterface $dateModification): static
    {
        $this->dateModification = $dateModification;
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

    public function getMouvements(): Collection
    {
        return $this->mouvements;
    }

    public function addMouvement(MouvementStock $mouvement): static
    {
        if (!$this->mouvements->contains($mouvement)) {
            $this->mouvements->add($mouvement);
            $mouvement->setStock($this);
        }
        return $this;
    }

    public function removeMouvement(MouvementStock $mouvement): static
    {
        if ($this->mouvements->removeElement($mouvement)) {
            if ($mouvement->getStock() === $this) {
                $mouvement->setStock(null);
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
            $alimentation->setStock($this);
        }
        return $this;
    }

    public function removeAlimentation(Alimentation $alimentation): static
    {
        if ($this->alimentations->removeElement($alimentation)) {
            if ($alimentation->getStock() === $this) {
                $alimentation->setStock(null);
            }
        }
        return $this;
    }

    public function getAlimentationsVolaille(): Collection
    {
        return $this->alimentationsVolaille;
    }

    public function addAlimentationsVolaille(AlimentationVolaille $alimentationsVolaille): static
    {
        if (!$this->alimentationsVolaille->contains($alimentationsVolaille)) {
            $this->alimentationsVolaille->add($alimentationsVolaille);
            $alimentationsVolaille->setStock($this);
        }
        return $this;
    }

    public function removeAlimentationsVolaille(AlimentationVolaille $alimentationsVolaille): static
    {
        if ($this->alimentationsVolaille->removeElement($alimentationsVolaille)) {
            if ($alimentationsVolaille->getStock() === $this) {
                $alimentationsVolaille->setStock(null);
            }
        }
        return $this;
    }

    public function estEnRupture(): bool
    {
        return (float)$this->quantiteActuelle <= (float)$this->quantiteMinimum;
    }

    public function estPerime(): bool
    {
        return $this->datePeremption && $this->datePeremption < new \DateTime();
    }

    public function joursAvantPeremption(): ?int
    {
        if (!$this->datePeremption) {
            return null;
        }
        
        $aujourd_hui = new \DateTime();
        $diff = $aujourd_hui->diff($this->datePeremption);
        
        return $this->datePeremption > $aujourd_hui ? $diff->days : -$diff->days;
    }

    public function calculerValeurStock(): void
    {
        if ($this->prixUnitaireMoyen) {
            $this->valeurStock = bcmul($this->quantiteActuelle, $this->prixUnitaireMoyen, 2);
        }
    }

    public function ajouterQuantite(string $quantite): void
    {
        $this->quantiteActuelle = bcadd($this->quantiteActuelle, $quantite, 3);
        $this->dateModification = new \DateTime();
    }

    public function retirerQuantite(string $quantite): void
    {
        $this->quantiteActuelle = bcsub($this->quantiteActuelle, $quantite, 3);
        $this->dateModification = new \DateTime();
    }

    public static function getTypesDisponibles(): array
    {
        return [
            self::TYPE_FOIN => 'Foin',
            self::TYPE_MAIS => 'Maïs',
            self::TYPE_SON => 'Son',
            self::TYPE_TOURTEAU => 'Tourteau',
            self::TYPE_PROVENDE => 'Provende',
            self::TYPE_CONCENTRE => 'Concentré',
            self::TYPE_SUPPLEMENT => 'Supplément',
            self::TYPE_MEDICAMENT => 'Médicament',
        ];
    }

    public static function getUnitesDisponibles(): array
    {
        return [
            self::UNITE_KG => 'Kg',
            self::UNITE_TONNE => 'Tonne',
            self::UNITE_LITRE => 'Litre',
            self::UNITE_SAC => 'Sac',
            self::UNITE_BOTTE => 'Botte',
        ];
    }

    public function __toString(): string
    {
        return $this->nom . ' (' . $this->quantiteActuelle . ' ' . $this->unite . ')';
    }
}