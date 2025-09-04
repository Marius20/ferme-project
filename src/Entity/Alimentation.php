<?php

namespace App\Entity;

use App\Repository\AlimentationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlimentationRepository::class)]
#[ORM\Table(name: 'alimentations')]
class Alimentation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 3)]
    private ?string $quantite = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 3, nullable: true)]
    private ?string $quantitePrevue = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\ManyToOne(targetEntity: Betail::class, inversedBy: 'alimentations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Betail $betail = null;

    #[ORM\ManyToOne(targetEntity: Stock::class, inversedBy: 'alimentations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Stock $stock = null;

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

    public function getQuantite(): ?string
    {
        return $this->quantite;
    }

    public function setQuantite(string $quantite): static
    {
        $this->quantite = $quantite;
        return $this;
    }

    public function getQuantitePrevue(): ?string
    {
        return $this->quantitePrevue;
    }

    public function setQuantitePrevue(?string $quantitePrevue): static
    {
        $this->quantitePrevue = $quantitePrevue;
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

    public function getStock(): ?Stock
    {
        return $this->stock;
    }

    public function setStock(?Stock $stock): static
    {
        $this->stock = $stock;
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

    public function calculerEcart(): ?string
    {
        if (!$this->quantitePrevue) {
            return null;
        }

        return bcsub($this->quantite, $this->quantitePrevue, 3);
    }

    public function calculerCout(): ?string
    {
        if (!$this->stock?->getPrixUnitaireMoyen()) {
            return null;
        }

        return bcmul($this->quantite, $this->stock->getPrixUnitaireMoyen(), 2);
    }

    public function __toString(): string
    {
        return $this->betail?->getNumeroIdentification() . ' - ' . $this->stock?->getNom() . ' - ' . $this->date->format('d/m/Y');
    }
}