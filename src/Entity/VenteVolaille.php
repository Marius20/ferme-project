<?php

namespace App\Entity;

use App\Repository\VenteVolailleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VenteVolailleRepository::class)]
#[ORM\Table(name: 'ventes_volaille')]
class VenteVolaille
{
    const STATUT_PREVUE = 'prevue';
    const STATUT_CONFIRMEE = 'confirmee';
    const STATUT_LIVREE = 'livree';
    const STATUT_PAYEE = 'payee';
    const STATUT_ANNULEE = 'annulee';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateVente = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $quantite = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 2, nullable: true)]
    private ?string $poidsUnitaire = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $poidsTotal = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $prixUnitaire = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2)]
    private ?string $montantTotal = null;

    #[ORM\Column(length: 255)]
    private ?string $acheteur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contactAcheteur = null;

    #[ORM\Column(length: 20)]
    private ?string $statut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateLivraison = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $datePaiement = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $modePaiement = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $numeroFacture = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\ManyToOne(targetEntity: Volaille::class, inversedBy: 'ventes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Volaille $volaille = null;

    #[ORM\ManyToOne(targetEntity: Employe::class)]
    private ?Employe $employe = null;

    public function __construct()
    {
        $this->dateCreation = new \DateTime();
        $this->statut = self::STATUT_PREVUE;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateVente(): ?\DateTimeInterface
    {
        return $this->dateVente;
    }

    public function setDateVente(\DateTimeInterface $dateVente): static
    {
        $this->dateVente = $dateVente;
        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;
        return $this;
    }

    public function getPoidsUnitaire(): ?string
    {
        return $this->poidsUnitaire;
    }

    public function setPoidsUnitaire(?string $poidsUnitaire): static
    {
        $this->poidsUnitaire = $poidsUnitaire;
        return $this;
    }

    public function getPoidsTotal(): ?string
    {
        return $this->poidsTotal;
    }

    public function setPoidsTotal(?string $poidsTotal): static
    {
        $this->poidsTotal = $poidsTotal;
        return $this;
    }

    public function getPrixUnitaire(): ?string
    {
        return $this->prixUnitaire;
    }

    public function setPrixUnitaire(string $prixUnitaire): static
    {
        $this->prixUnitaire = $prixUnitaire;
        return $this;
    }

    public function getMontantTotal(): ?string
    {
        return $this->montantTotal;
    }

    public function setMontantTotal(string $montantTotal): static
    {
        $this->montantTotal = $montantTotal;
        return $this;
    }

    public function getAcheteur(): ?string
    {
        return $this->acheteur;
    }

    public function setAcheteur(string $acheteur): static
    {
        $this->acheteur = $acheteur;
        return $this;
    }

    public function getContactAcheteur(): ?string
    {
        return $this->contactAcheteur;
    }

    public function setContactAcheteur(?string $contactAcheteur): static
    {
        $this->contactAcheteur = $contactAcheteur;
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

    public function getDateLivraison(): ?\DateTimeInterface
    {
        return $this->dateLivraison;
    }

    public function setDateLivraison(?\DateTimeInterface $dateLivraison): static
    {
        $this->dateLivraison = $dateLivraison;
        return $this;
    }

    public function getDatePaiement(): ?\DateTimeInterface
    {
        return $this->datePaiement;
    }

    public function setDatePaiement(?\DateTimeInterface $datePaiement): static
    {
        $this->datePaiement = $datePaiement;
        return $this;
    }

    public function getModePaiement(): ?string
    {
        return $this->modePaiement;
    }

    public function setModePaiement(?string $modePaiement): static
    {
        $this->modePaiement = $modePaiement;
        return $this;
    }

    public function getNumeroFacture(): ?string
    {
        return $this->numeroFacture;
    }

    public function setNumeroFacture(?string $numeroFacture): static
    {
        $this->numeroFacture = $numeroFacture;
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

    public function calculerPoidsTotal(): void
    {
        if ($this->poidsUnitaire) {
            $this->poidsTotal = bcmul($this->poidsUnitaire, $this->quantite, 2);
        }
    }

    public function calculerMontantTotal(): void
    {
        $this->montantTotal = bcmul($this->prixUnitaire, $this->quantite, 2);
    }

    public function confirmer(): void
    {
        $this->statut = self::STATUT_CONFIRMEE;
    }

    public function livrer(): void
    {
        $this->statut = self::STATUT_LIVREE;
        $this->dateLivraison = new \DateTime();
        
        // Réduire l'effectif de la volaille
        if ($this->volaille) {
            $nouvelEffectif = $this->volaille->getEffectif() - $this->quantite;
            $this->volaille->setEffectif(max(0, $nouvelEffectif));
        }
    }

    public function marquerPayee(): void
    {
        $this->statut = self::STATUT_PAYEE;
        $this->datePaiement = new \DateTime();
    }

    public function annuler(): void
    {
        $this->statut = self::STATUT_ANNULEE;
    }

    public function isPrevue(): bool
    {
        return $this->statut === self::STATUT_PREVUE;
    }

    public function isConfirmee(): bool
    {
        return $this->statut === self::STATUT_CONFIRMEE;
    }

    public function isLivree(): bool
    {
        return $this->statut === self::STATUT_LIVREE;
    }

    public function isPayee(): bool
    {
        return $this->statut === self::STATUT_PAYEE;
    }

    public function isAnnulee(): bool
    {
        return $this->statut === self::STATUT_ANNULEE;
    }

    public static function getStatutsDisponibles(): array
    {
        return [
            self::STATUT_PREVUE => 'Prévue',
            self::STATUT_CONFIRMEE => 'Confirmée',
            self::STATUT_LIVREE => 'Livrée',
            self::STATUT_PAYEE => 'Payée',
            self::STATUT_ANNULEE => 'Annulée',
        ];
    }

    public function __toString(): string
    {
        return $this->volaille?->getNumeroLot() . ' - ' . $this->quantite . ' unités - ' . $this->acheteur;
    }
}