<?php

namespace App\Entity;

use App\Repository\PresenceEmployeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PresenceEmployeRepository::class)]
#[ORM\Table(name: 'presences_employes')]
class PresenceEmploye
{
    const TYPE_TRAVAIL = 'travail';
    const TYPE_CONGE = 'conge';
    const TYPE_MALADIE = 'maladie';
    const TYPE_ABSENCE = 'absence';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $heureArrivee = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $heureDepart = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $pauseDebut = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $pauseFin = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    private ?string $heuresTravaillees = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    private ?string $heuresSupplementaires = null;

    #[ORM\Column(length: 20)]
    private ?string $type = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\ManyToOne(targetEntity: Employe::class, inversedBy: 'presences')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Employe $employe = null;

    public function __construct()
    {
        $this->dateCreation = new \DateTime();
        $this->type = self::TYPE_TRAVAIL;
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

    public function getHeureArrivee(): ?\DateTimeInterface
    {
        return $this->heureArrivee;
    }

    public function setHeureArrivee(?\DateTimeInterface $heureArrivee): static
    {
        $this->heureArrivee = $heureArrivee;
        return $this;
    }

    public function getHeureDepart(): ?\DateTimeInterface
    {
        return $this->heureDepart;
    }

    public function setHeureDepart(?\DateTimeInterface $heureDepart): static
    {
        $this->heureDepart = $heureDepart;
        return $this;
    }

    public function getPauseDebut(): ?\DateTimeInterface
    {
        return $this->pauseDebut;
    }

    public function setPauseDebut(?\DateTimeInterface $pauseDebut): static
    {
        $this->pauseDebut = $pauseDebut;
        return $this;
    }

    public function getPauseFin(): ?\DateTimeInterface
    {
        return $this->pauseFin;
    }

    public function setPauseFin(?\DateTimeInterface $pauseFin): static
    {
        $this->pauseFin = $pauseFin;
        return $this;
    }

    public function getHeuresTravaillees(): ?string
    {
        return $this->heuresTravaillees;
    }

    public function setHeuresTravaillees(?string $heuresTravaillees): static
    {
        $this->heuresTravaillees = $heuresTravaillees;
        return $this;
    }

    public function getHeuresSupplementaires(): ?string
    {
        return $this->heuresSupplementaires;
    }

    public function setHeuresSupplementaires(?string $heuresSupplementaires): static
    {
        $this->heuresSupplementaires = $heuresSupplementaires;
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

    public function getEmploye(): ?Employe
    {
        return $this->employe;
    }

    public function setEmploye(?Employe $employe): static
    {
        $this->employe = $employe;
        return $this;
    }

    public function calculerHeuresTravaillees(): void
    {
        if (!$this->heureArrivee || !$this->heureDepart) {
            return;
        }

        $arrivee = clone $this->heureArrivee;
        $depart = clone $this->heureDepart;
        
        $duree = $depart->diff($arrivee);
        $heures = $duree->h + ($duree->i / 60);

        // Soustraire la pause si elle existe
        if ($this->pauseDebut && $this->pauseFin) {
            $pauseDebut = clone $this->pauseDebut;
            $pauseFin = clone $this->pauseFin;
            $dureePause = $pauseFin->diff($pauseDebut);
            $heuresPause = $dureePause->h + ($dureePause->i / 60);
            $heures -= $heuresPause;
        }

        $this->heuresTravaillees = number_format($heures, 2);

        // Calculer les heures supplémentaires (> 8h par jour)
        if ($heures > 8) {
            $this->heuresSupplementaires = number_format($heures - 8, 2);
        } else {
            $this->heuresSupplementaires = '0.00';
        }
    }

    public function isPresent(): bool
    {
        return $this->type === self::TYPE_TRAVAIL && $this->heureArrivee !== null;
    }

    public static function getTypesDisponibles(): array
    {
        return [
            self::TYPE_TRAVAIL => 'Travail',
            self::TYPE_CONGE => 'Congé',
            self::TYPE_MALADIE => 'Maladie',
            self::TYPE_ABSENCE => 'Absence',
        ];
    }

    public function __toString(): string
    {
        return $this->employe?->getNomComplet() . ' - ' . $this->date->format('d/m/Y');
    }
}