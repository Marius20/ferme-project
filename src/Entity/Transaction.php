<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
#[ORM\Table(name: 'transactions')]
class Transaction
{
    const TYPE_RECETTE = 'recette';
    const TYPE_DEPENSE = 'depense';

    const CATEGORIE_VENTE_ANIMAL = 'vente_animal';
    const CATEGORIE_VENTE_OEUFS = 'vente_oeufs';
    const CATEGORIE_VENTE_PRODUITS = 'vente_produits';
    const CATEGORIE_SUBVENTION = 'subvention';
    const CATEGORIE_AUTRE_RECETTE = 'autre_recette';

    const CATEGORIE_ACHAT_ALIMENT = 'achat_aliment';
    const CATEGORIE_ACHAT_MEDICAMENT = 'achat_medicament';
    const CATEGORIE_ACHAT_MATERIEL = 'achat_materiel';
    const CATEGORIE_ACHAT_ANIMAL = 'achat_animal';
    const CATEGORIE_SALAIRE = 'salaire';
    const CATEGORIE_VETERINAIRE = 'veterinaire';
    const CATEGORIE_TRANSPORT = 'transport';
    const CATEGORIE_ELECTRICITE = 'electricite';
    const CATEGORIE_EAU = 'eau';
    const CATEGORIE_ASSURANCE = 'assurance';
    const CATEGORIE_AUTRE_DEPENSE = 'autre_depense';

    const STATUT_EN_ATTENTE = 'en_attente';
    const STATUT_VALIDEE = 'validee';
    const STATUT_ANNULEE = 'annulee';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $type = null;

    #[ORM\Column(length: 50)]
    private ?string $categorie = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2)]
    private ?string $montant = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tiers = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $numeroFacture = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $modePaiement = null;

    #[ORM\Column(length: 20)]
    private ?string $statut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateTransaction = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateEcheance = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateModification = null;

    #[ORM\ManyToOne(targetEntity: Ferme::class, inversedBy: 'transactions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ferme $ferme = null;

    #[ORM\ManyToOne(targetEntity: Employe::class)]
    private ?Employe $employe = null;

    #[ORM\ManyToOne(targetEntity: Betail::class)]
    private ?Betail $betail = null;

    #[ORM\ManyToOne(targetEntity: Volaille::class)]
    private ?Volaille $volaille = null;

    #[ORM\ManyToOne(targetEntity: Stock::class)]
    private ?Stock $stock = null;

    public function __construct()
    {
        $this->dateCreation = new \DateTime();
        $this->statut = self::STATUT_EN_ATTENTE;
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

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): static
    {
        $this->categorie = $categorie;
        return $this;
    }

    public function getMontant(): ?string
    {
        return $this->montant;
    }

    public function setMontant(string $montant): static
    {
        $this->montant = $montant;
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

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;
        return $this;
    }

    public function getTiers(): ?string
    {
        return $this->tiers;
    }

    public function setTiers(?string $tiers): static
    {
        $this->tiers = $tiers;
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

    public function getModePaiement(): ?string
    {
        return $this->modePaiement;
    }

    public function setModePaiement(?string $modePaiement): static
    {
        $this->modePaiement = $modePaiement;
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

    public function getDateTransaction(): ?\DateTimeInterface
    {
        return $this->dateTransaction;
    }

    public function setDateTransaction(\DateTimeInterface $dateTransaction): static
    {
        $this->dateTransaction = $dateTransaction;
        return $this;
    }

    public function getDateEcheance(): ?\DateTimeInterface
    {
        return $this->dateEcheance;
    }

    public function setDateEcheance(?\DateTimeInterface $dateEcheance): static
    {
        $this->dateEcheance = $dateEcheance;
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

    public function getStock(): ?Stock
    {
        return $this->stock;
    }

    public function setStock(?Stock $stock): static
    {
        $this->stock = $stock;
        return $this;
    }

    public function isRecette(): bool
    {
        return $this->type === self::TYPE_RECETTE;
    }

    public function isDepense(): bool
    {
        return $this->type === self::TYPE_DEPENSE;
    }

    public function isEnRetard(): bool
    {
        return $this->dateEcheance && $this->dateEcheance < new \DateTime() && $this->statut === self::STATUT_EN_ATTENTE;
    }

    public static function getCategoriesRecettes(): array
    {
        return [
            self::CATEGORIE_VENTE_ANIMAL => 'Vente d\'animaux',
            self::CATEGORIE_VENTE_OEUFS => 'Vente d\'œufs',
            self::CATEGORIE_VENTE_PRODUITS => 'Vente de produits',
            self::CATEGORIE_SUBVENTION => 'Subvention',
            self::CATEGORIE_AUTRE_RECETTE => 'Autre recette',
        ];
    }

    public static function getCategoriesDepenses(): array
    {
        return [
            self::CATEGORIE_ACHAT_ALIMENT => 'Achat d\'aliments',
            self::CATEGORIE_ACHAT_MEDICAMENT => 'Achat de médicaments',
            self::CATEGORIE_ACHAT_MATERIEL => 'Achat de matériel',
            self::CATEGORIE_ACHAT_ANIMAL => 'Achat d\'animaux',
            self::CATEGORIE_SALAIRE => 'Salaires',
            self::CATEGORIE_VETERINAIRE => 'Frais vétérinaires',
            self::CATEGORIE_TRANSPORT => 'Transport',
            self::CATEGORIE_ELECTRICITE => 'Électricité',
            self::CATEGORIE_EAU => 'Eau',
            self::CATEGORIE_ASSURANCE => 'Assurance',
            self::CATEGORIE_AUTRE_DEPENSE => 'Autre dépense',
        ];
    }

    public static function getCategoriesPourType(string $type): array
    {
        return match($type) {
            self::TYPE_RECETTE => self::getCategoriesRecettes(),
            self::TYPE_DEPENSE => self::getCategoriesDepenses(),
            default => [],
        };
    }

    public function __toString(): string
    {
        return $this->description . ' - ' . $this->montant . '€ (' . $this->dateTransaction->format('d/m/Y') . ')';
    }
}