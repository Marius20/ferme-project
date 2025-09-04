<?php

namespace App\Entity;

use App\Repository\TacheEmployeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TacheEmployeRepository::class)]
#[ORM\Table(name: 'taches_employes')]
class TacheEmploye
{
    const TYPE_DISTRIBUTION = 'distribution';
    const TYPE_SOINS = 'soins';
    const TYPE_NETTOYAGE = 'nettoyage';
    const TYPE_RECOLTE_OEUFS = 'recolte_oeufs';
    const TYPE_SURVEILLANCE = 'surveillance';
    const TYPE_MAINTENANCE = 'maintenance';
    const TYPE_TRANSPORT = 'transport';
    const TYPE_AUTRE = 'autre';

    const PRIORITE_BASSE = 'basse';
    const PRIORITE_NORMALE = 'normale';
    const PRIORITE_HAUTE = 'haute';
    const PRIORITE_URGENTE = 'urgente';

    const STATUT_PLANIFIEE = 'planifiee';
    const STATUT_EN_COURS = 'en_cours';
    const STATUT_TERMINEE = 'terminee';
    const STATUT_ANNULEE = 'annulee';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null;

    #[ORM\Column(length: 20)]
    private ?string $priorite = null;

    #[ORM\Column(length: 20)]
    private ?string $statut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datePrevue = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $dureeEstimee = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $dureeReelle = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaires = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\ManyToOne(targetEntity: Employe::class, inversedBy: 'taches')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Employe $employe = null;

    #[ORM\ManyToOne(targetEntity: Betail::class)]
    private ?Betail $betail = null;

    #[ORM\ManyToOne(targetEntity: Volaille::class)]
    private ?Volaille $volaille = null;

    public function __construct()
    {
        $this->dateCreation = new \DateTime();
        $this->statut = self::STATUT_PLANIFIEE;
        $this->priorite = self::PRIORITE_NORMALE;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getPriorite(): ?string
    {
        return $this->priorite;
    }

    public function setPriorite(string $priorite): static
    {
        $this->priorite = $priorite;
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

    public function getDatePrevue(): ?\DateTimeInterface
    {
        return $this->datePrevue;
    }

    public function setDatePrevue(\DateTimeInterface $datePrevue): static
    {
        $this->datePrevue = $datePrevue;
        return $this;
    }

    public function getDureeEstimee(): ?int
    {
        return $this->dureeEstimee;
    }

    public function setDureeEstimee(?int $dureeEstimee): static
    {
        $this->dureeEstimee = $dureeEstimee;
        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;
        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;
        return $this;
    }

    public function getDureeReelle(): ?int
    {
        return $this->dureeReelle;
    }

    public function setDureeReelle(?int $dureeReelle): static
    {
        $this->dureeReelle = $dureeReelle;
        return $this;
    }

    public function getCommentaires(): ?string
    {
        return $this->commentaires;
    }

    public function setCommentaires(?string $commentaires): static
    {
        $this->commentaires = $commentaires;
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

    public function getEmploye(): ?Employe
    {
        return $this->employe;
    }

    public function setEmploye(?Employe $employe): static
    {
        $this->employe = $employe;
        return $this;
    }

    public function getBetail(): ?Betail
    {
        return $this->betail;
    }

    public function setBetail(?Betail $betail): static
    {
        $this->betail = $betail;
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

    public function commencer(): void
    {
        $this->statut = self::STATUT_EN_COURS;
        $this->dateDebut = new \DateTime();
    }

    public function terminer(): void
    {
        $this->statut = self::STATUT_TERMINEE;
        $this->dateFin = new \DateTime();
        
        if ($this->dateDebut) {
            $this->dureeReelle = $this->dateDebut->diff($this->dateFin)->i;
        }
    }

    public function annuler(): void
    {
        $this->statut = self::STATUT_ANNULEE;
    }

    public function isTerminee(): bool
    {
        return $this->statut === self::STATUT_TERMINEE;
    }

    public function isEnRetard(): bool
    {
        return $this->datePrevue < new \DateTime() && !$this->isTerminee();
    }

    public function calculerDureeReelle(): void
    {
        if ($this->dateDebut && $this->dateFin) {
            $this->dureeReelle = $this->dateDebut->diff($this->dateFin)->i;
        }
    }

    public static function getTypesDisponibles(): array
    {
        return [
            self::TYPE_DISTRIBUTION => 'Distribution d\'aliments',
            self::TYPE_SOINS => 'Soins aux animaux',
            self::TYPE_NETTOYAGE => 'Nettoyage',
            self::TYPE_RECOLTE_OEUFS => 'Récolte d\'œufs',
            self::TYPE_SURVEILLANCE => 'Surveillance',
            self::TYPE_MAINTENANCE => 'Maintenance',
            self::TYPE_TRANSPORT => 'Transport',
            self::TYPE_AUTRE => 'Autre',
        ];
    }

    public static function getPrioritesDisponibles(): array
    {
        return [
            self::PRIORITE_BASSE => 'Basse',
            self::PRIORITE_NORMALE => 'Normale',
            self::PRIORITE_HAUTE => 'Haute',
            self::PRIORITE_URGENTE => 'Urgente',
        ];
    }

    public function __toString(): string
    {
        return $this->titre . ' (' . $this->datePrevue->format('d/m/Y H:i') . ')';
    }
}