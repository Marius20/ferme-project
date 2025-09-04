<?php

namespace App\Entity;

use App\Repository\ProductionOeufRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductionOeufRepository::class)]
#[ORM\Table(name: 'productions_oeufs')]
class ProductionOeuf
{
    const QUALITE_EXTRA = 'extra';
    const QUALITE_PREMIERE = 'premiere';
    const QUALITE_SECONDE = 'seconde';
    const QUALITE_DECHET = 'dechet';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $nombreOeufs = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $nombreExtra = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $nombrePremiere = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $nombreSeconde = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $nombreDechet = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 2, nullable: true)]
    private ?string $poidsMoyen = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $effectifLors = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\ManyToOne(targetEntity: Volaille::class, inversedBy: 'productionsOeufs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Volaille $volaille = null;

    #[ORM\ManyToOne(targetEntity: Employe::class)]
    private ?Employe $employe = null;

    public function __construct()
    {
        $this->dateCreation = new \DateTime();
        $this->date = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;
        return $this;
    }

    public function getNombreOeufs(): ?int
    {
        return $this->nombreOeufs;
    }

    public function setNombreOeufs(int $nombreOeufs): static
    {
        $this->nombreOeufs = $nombreOeufs;
        return $this;
    }

    public function getNombreExtra(): ?int
    {
        return $this->nombreExtra;
    }

    public function setNombreExtra(?int $nombreExtra): static
    {
        $this->nombreExtra = $nombreExtra;
        return $this;
    }

    public function getNombrePremiere(): ?int
    {
        return $this->nombrePremiere;
    }

    public function setNombrePremiere(?int $nombrePremiere): static
    {
        $this->nombrePremiere = $nombrePremiere;
        return $this;
    }

    public function getNombreSeconde(): ?int
    {
        return $this->nombreSeconde;
    }

    public function setNombreSeconde(?int $nombreSeconde): static
    {
        $this->nombreSeconde = $nombreSeconde;
        return $this;
    }

    public function getNombreDechet(): ?int
    {
        return $this->nombreDechet;
    }

    public function setNombreDechet(?int $nombreDechet): static
    {
        $this->nombreDechet = $nombreDechet;
        return $this;
    }

    public function getPoidsMoyen(): ?string
    {
        return $this->poidsMoyen;
    }

    public function setPoidsMoyen(?string $poidsMoyen): static
    {
        $this->poidsMoyen = $poidsMoyen;
        return $this;
    }

    public function getEffectifLors(): ?int
    {
        return $this->effectifLors;
    }

    public function setEffectifLors(int $effectifLors): static
    {
        $this->effectifLors = $effectifLors;
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

    public function getVolaille(): ?Volaille
    {
        return $this->volaille;
    }

    public function setVolaille(?Volaille $volaille): static
    {
        $this->volaille = $volaille;
        return $this;
    }

    public function getEmploye(): ?Employe
    {
        return $this->employe;
    }

    public function setEmploye(?Employe $employe): static
    {
        $this->employe = $employe;
        return $this;
    }

    public function calculerTauxPonte(): float
    {
        if ($this->effectifLors == 0) {
            return 0;
        }

        return ($this->nombreOeufs / $this->effectifLors) * 100;
    }

    public function calculerPourcentageQualite(string $qualite): float
    {
        if ($this->nombreOeufs == 0) {
            return 0;
        }

        $nombre = match($qualite) {
            self::QUALITE_EXTRA => $this->nombreExtra ?? 0,
            self::QUALITE_PREMIERE => $this->nombrePremiere ?? 0,
            self::QUALITE_SECONDE => $this->nombreSeconde ?? 0,
            self::QUALITE_DECHET => $this->nombreDechet ?? 0,
            default => 0,
        };

        return ($nombre / $this->nombreOeufs) * 100;
    }

    public function verifierCoherence(): array
    {
        $erreurs = [];
        
        $totalQualite = ($this->nombreExtra ?? 0) + ($this->nombrePremiere ?? 0) + 
                       ($this->nombreSeconde ?? 0) + ($this->nombreDechet ?? 0);
        
        if ($totalQualite > 0 && $totalQualite != $this->nombreOeufs) {
            $erreurs[] = 'Le total par qualité ne correspond pas au nombre total d\'œufs';
        }

        if ($this->effectifLors > 0 && $this->nombreOeufs > $this->effectifLors) {
            $erreurs[] = 'Le nombre d\'œufs ne peut pas être supérieur à l\'effectif';
        }

        return $erreurs;
    }

    public static function getQualitesDisponibles(): array
    {
        return [
            self::QUALITE_EXTRA => 'Extra',
            self::QUALITE_PREMIERE => 'Première',
            self::QUALITE_SECONDE => 'Seconde',
            self::QUALITE_DECHET => 'Déchet',
        ];
    }

    public function __toString(): string
    {
        return $this->volaille?->getNumeroLot() . ' - ' . $this->nombreOeufs . ' œufs - ' . $this->date->format('d/m/Y');
    }
}