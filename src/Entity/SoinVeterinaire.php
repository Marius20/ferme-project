<?php

namespace App\Entity;

use App\Repository\SoinVeterinaireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SoinVeterinaireRepository::class)]
#[ORM\Table(name: 'soins_veterinaires')]
class SoinVeterinaire
{
    const TYPE_VACCINATION = 'vaccination';
    const TYPE_VERMIFUGE = 'vermifuge';
    const TYPE_TRAITEMENT = 'traitement';
    const TYPE_CONSULTATION = 'consultation';
    const TYPE_CHIRURGIE = 'chirurgie';
    const TYPE_PREVENTION = 'prevention';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateSoin = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $veterinaire = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $medicament = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 3, nullable: true)]
    private ?string $dosage = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $uniteDosage = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $cout = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateRappel = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $delaiAttente = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\ManyToOne(targetEntity: Betail::class, inversedBy: 'soinsVeterinaires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Betail $betail = null;

    #[ORM\ManyToOne(targetEntity: Employe::class)]
    private ?Employe $employe = null;

    public function __construct()
    {
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getDateSoin(): ?\DateTimeInterface
    {
        return $this->dateSoin;
    }

    public function setDateSoin(\DateTimeInterface $dateSoin): static
    {
        $this->dateSoin = $dateSoin;
        return $this;
    }

    public function getVeterinaire(): ?string
    {
        return $this->veterinaire;
    }

    public function setVeterinaire(?string $veterinaire): static
    {
        $this->veterinaire = $veterinaire;
        return $this;
    }

    public function getMedicament(): ?string
    {
        return $this->medicament;
    }

    public function setMedicament(?string $medicament): static
    {
        $this->medicament = $medicament;
        return $this;
    }

    public function getDosage(): ?string
    {
        return $this->dosage;
    }

    public function setDosage(?string $dosage): static
    {
        $this->dosage = $dosage;
        return $this;
    }

    public function getUniteDosage(): ?string
    {
        return $this->uniteDosage;
    }

    public function setUniteDosage(?string $uniteDosage): static
    {
        $this->uniteDosage = $uniteDosage;
        return $this;
    }

    public function getCout(): ?string
    {
        return $this->cout;
    }

    public function setCout(?string $cout): static
    {
        $this->cout = $cout;
        return $this;
    }

    public function getDateRappel(): ?\DateTimeInterface
    {
        return $this->dateRappel;
    }

    public function setDateRappel(?\DateTimeInterface $dateRappel): static
    {
        $this->dateRappel = $dateRappel;
        return $this;
    }

    public function getDelaiAttente(): ?int
    {
        return $this->delaiAttente;
    }

    public function setDelaiAttente(?int $delaiAttente): static
    {
        $this->delaiAttente = $delaiAttente;
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

    public function getEmploye(): ?Employe
    {
        return $this->employe;
    }

    public function setEmploye(?Employe $employe): static
    {
        $this->employe = $employe;
        return $this;
    }

    public function calculerDateFinAttente(): ?\DateTime
    {
        if (!$this->delaiAttente) {
            return null;
        }

        $dateFinAttente = clone $this->dateSoin;
        $dateFinAttente->modify("+{$this->delaiAttente} days");
        
        return $dateFinAttente;
    }

    public function isDelaiAttenteEcoule(): bool
    {
        $dateFinAttente = $this->calculerDateFinAttente();
        if (!$dateFinAttente) {
            return true;
        }

        return $dateFinAttente <= new \DateTime();
    }

    public function isRappelDu(): bool
    {
        return $this->dateRappel && $this->dateRappel <= new \DateTime();
    }

    public static function getTypesDisponibles(): array
    {
        return [
            self::TYPE_VACCINATION => 'Vaccination',
            self::TYPE_VERMIFUGE => 'Vermifuge',
            self::TYPE_TRAITEMENT => 'Traitement',
            self::TYPE_CONSULTATION => 'Consultation',
            self::TYPE_CHIRURGIE => 'Chirurgie',
            self::TYPE_PREVENTION => 'Prévention',
        ];
    }

    public function __toString(): string
    {
        return $this->betail?->getNumeroIdentification() . ' - ' . $this->description . ' - ' . $this->dateSoin->format('d/m/Y');
    }
}