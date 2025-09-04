<?php

namespace App\Entity;

use App\Repository\ReproductionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReproductionRepository::class)]
#[ORM\Table(name: 'reproductions')]
class Reproduction
{
    const TYPE_SAILLIE = 'saillie';
    const TYPE_INSEMINATION = 'insemination';

    const STATUT_PLANIFIEE = 'planifiee';
    const STATUT_REALISEE = 'realisee';
    const STATUT_GESTATION = 'gestation';
    const STATUT_MISE_BAS = 'mise_bas';
    const STATUT_ECHEC = 'echec';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $type = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateReproduction = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateGestation = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateMiseBas = null;

    #[ORM\Column(length: 20)]
    private ?string $statut = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $nombreNaissances = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $nombreVivants = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $nombreMorts = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pere = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\ManyToOne(targetEntity: Betail::class, inversedBy: 'reproductions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Betail $betail = null;

    public function __construct()
    {
        $this->dateCreation = new \DateTime();
        $this->statut = self::STATUT_PLANIFIEE;
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

    public function getDateReproduction(): ?\DateTimeInterface
    {
        return $this->dateReproduction;
    }

    public function setDateReproduction(\DateTimeInterface $dateReproduction): static
    {
        $this->dateReproduction = $dateReproduction;
        return $this;
    }

    public function getDateGestation(): ?\DateTimeInterface
    {
        return $this->dateGestation;
    }

    public function setDateGestation(?\DateTimeInterface $dateGestation): static
    {
        $this->dateGestation = $dateGestation;
        return $this;
    }

    public function getDateMiseBas(): ?\DateTimeInterface
    {
        return $this->dateMiseBas;
    }

    public function setDateMiseBas(?\DateTimeInterface $dateMiseBas): static
    {
        $this->dateMiseBas = $dateMiseBas;
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

    public function getNombreNaissances(): ?int
    {
        return $this->nombreNaissances;
    }

    public function setNombreNaissances(?int $nombreNaissances): static
    {
        $this->nombreNaissances = $nombreNaissances;
        return $this;
    }

    public function getNombreVivants(): ?int
    {
        return $this->nombreVivants;
    }

    public function setNombreVivants(?int $nombreVivants): static
    {
        $this->nombreVivants = $nombreVivants;
        return $this;
    }

    public function getNombreMorts(): ?int
    {
        return $this->nombreMorts;
    }

    public function setNombreMorts(?int $nombreMorts): static
    {
        $this->nombreMorts = $nombreMorts;
        return $this;
    }

    public function getPere(): ?string
    {
        return $this->pere;
    }

    public function setPere(?string $pere): static
    {
        $this->pere = $pere;
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

    public function getBetail(): ?Betail
    {
        return $this->betail;
    }

    public function setBetail(?Betail $betail): static
    {
        $this->betail = $betail;
        return $this;
    }

    public function calculerDateMiseBasPrevue(): ?\DateTime
    {
        if (!$this->dateGestation || !$this->betail) {
            return null;
        }

        $dureeGestationJours = match($this->betail->getType()) {
            Betail::TYPE_BOVIN => 285,
            Betail::TYPE_OVIN => 150,
            Betail::TYPE_CAPRIN => 150,
            Betail::TYPE_PORCIN => 115,
            Betail::TYPE_EQUIN => 340,
            default => 150,
        };

        $datePrevue = clone $this->dateGestation;
        $datePrevue->modify("+{$dureeGestationJours} days");
        
        return $datePrevue;
    }

    public function getTauxReussite(): ?float
    {
        if (!$this->nombreNaissances) {
            return null;
        }

        return ($this->nombreVivants / $this->nombreNaissances) * 100;
    }

    public function confirmerGestation(): void
    {
        $this->statut = self::STATUT_GESTATION;
        $this->dateGestation = new \DateTime();
    }

    public function enregistrerMiseBas(): void
    {
        $this->statut = self::STATUT_MISE_BAS;
        $this->dateMiseBas = new \DateTime();
    }

    public function marquerEchec(): void
    {
        $this->statut = self::STATUT_ECHEC;
    }

    public static function getTypesDisponibles(): array
    {
        return [
            self::TYPE_SAILLIE => 'Saillie naturelle',
            self::TYPE_INSEMINATION => 'Insémination artificielle',
        ];
    }

    public function __toString(): string
    {
        return $this->betail?->getNumeroIdentification() . ' - ' . $this->dateReproduction->format('d/m/Y');
    }
}