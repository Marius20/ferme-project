<?php

namespace App\Entity;

use App\Repository\FermeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FermeRepository::class)]
#[ORM\Table(name: 'fermes')]
class Ferme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $localisation = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $superficie = null;

    #[ORM\Column(length: 100)]
    private ?string $type = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateModification = null;

    #[ORM\OneToMany(targetEntity: Betail::class, mappedBy: 'ferme', orphanRemoval: true)]
    private Collection $betails;

    #[ORM\OneToMany(targetEntity: Batiment::class, mappedBy: 'ferme', orphanRemoval: true)]
    private Collection $batiments;

    #[ORM\OneToMany(targetEntity: Volaille::class, mappedBy: 'ferme', orphanRemoval: true)]
    private Collection $volailles;

    #[ORM\OneToMany(targetEntity: Stock::class, mappedBy: 'ferme', orphanRemoval: true)]
    private Collection $stocks;

    #[ORM\OneToMany(targetEntity: Employe::class, mappedBy: 'ferme', orphanRemoval: true)]
    private Collection $employes;

    #[ORM\OneToMany(targetEntity: Transaction::class, mappedBy: 'ferme', orphanRemoval: true)]
    private Collection $transactions;

    #[ORM\OneToMany(targetEntity: ZoneFerme::class, mappedBy: 'ferme', orphanRemoval: true)]
    private Collection $zonesFermes;

    #[ORM\OneToMany(targetEntity: FamilleBetailFerme::class, mappedBy: 'ferme', orphanRemoval: true)]
    private Collection $famillesBetailFerme;

    public function __construct()
    {
        $this->betails = new ArrayCollection();
        $this->batiments = new ArrayCollection();
        $this->volailles = new ArrayCollection();
        $this->stocks = new ArrayCollection();
        $this->employes = new ArrayCollection();
        $this->transactions = new ArrayCollection();
        $this->zonesFermes = new ArrayCollection();
        $this->famillesBetailFerme = new ArrayCollection();
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

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(?string $localisation): static
    {
        $this->localisation = $localisation;
        return $this;
    }

    public function getSuperficie(): ?string
    {
        return $this->superficie;
    }

    public function setSuperficie(?string $superficie): static
    {
        $this->superficie = $superficie;
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

    public function getBetails(): Collection
    {
        return $this->betails;
    }

    public function addBetail(Betail $betail): static
    {
        if (!$this->betails->contains($betail)) {
            $this->betails->add($betail);
            $betail->setFerme($this);
        }
        return $this;
    }

    public function removeBetail(Betail $betail): static
    {
        if ($this->betails->removeElement($betail)) {
            if ($betail->getFerme() === $this) {
                $betail->setFerme(null);
            }
        }
        return $this;
    }

    public function getBatiments(): Collection
    {
        return $this->batiments;
    }

    public function addBatiment(Batiment $batiment): static
    {
        if (!$this->batiments->contains($batiment)) {
            $this->batiments->add($batiment);
            $batiment->setFerme($this);
        }
        return $this;
    }

    public function removeBatiment(Batiment $batiment): static
    {
        if ($this->batiments->removeElement($batiment)) {
            if ($batiment->getFerme() === $this) {
                $batiment->setFerme(null);
            }
        }
        return $this;
    }

    public function getVolailles(): Collection
    {
        return $this->volailles;
    }

    public function addVolaille(Volaille $volaille): static
    {
        if (!$this->volailles->contains($volaille)) {
            $this->volailles->add($volaille);
            $volaille->setFerme($this);
        }
        return $this;
    }

    public function removeVolaille(Volaille $volaille): static
    {
        if ($this->volailles->removeElement($volaille)) {
            if ($volaille->getFerme() === $this) {
                $volaille->setFerme(null);
            }
        }
        return $this;
    }

    public function getStocks(): Collection
    {
        return $this->stocks;
    }

    public function addStock(Stock $stock): static
    {
        if (!$this->stocks->contains($stock)) {
            $this->stocks->add($stock);
            $stock->setFerme($this);
        }
        return $this;
    }

    public function removeStock(Stock $stock): static
    {
        if ($this->stocks->removeElement($stock)) {
            if ($stock->getFerme() === $this) {
                $stock->setFerme(null);
            }
        }
        return $this;
    }

    public function getEmployes(): Collection
    {
        return $this->employes;
    }

    public function addEmploye(Employe $employe): static
    {
        if (!$this->employes->contains($employe)) {
            $this->employes->add($employe);
            $employe->setFerme($this);
        }
        return $this;
    }

    public function removeEmploye(Employe $employe): static
    {
        if ($this->employes->removeElement($employe)) {
            if ($employe->getFerme() === $this) {
                $employe->setFerme(null);
            }
        }
        return $this;
    }

    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): static
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setFerme($this);
        }
        return $this;
    }

    public function removeTransaction(Transaction $transaction): static
    {
        if ($this->transactions->removeElement($transaction)) {
            if ($transaction->getFerme() === $this) {
                $transaction->setFerme(null);
            }
        }
        return $this;
    }

    public function getEffectifTotal(): int
    {
        return $this->betails->count() + $this->volailles->count();
    }

    public function getEffectifParType(): array
    {
        $effectifs = [];
        
        foreach ($this->betails as $betail) {
            $type = $betail->getType();
            $effectifs[$type] = ($effectifs[$type] ?? 0) + 1;
        }
        
        foreach ($this->volailles as $volaille) {
            $type = $volaille->getType();
            $effectifs[$type] = ($effectifs[$type] ?? 0) + $volaille->getEffectif();
        }
        
        return $effectifs;
    }

    public function getZonesFermes(): Collection
    {
        return $this->zonesFermes;
    }

    public function addZoneFerme(ZoneFerme $zoneFerme): static
    {
        if (!$this->zonesFermes->contains($zoneFerme)) {
            $this->zonesFermes->add($zoneFerme);
            $zoneFerme->setFerme($this);
        }
        return $this;
    }

    public function removeZoneFerme(ZoneFerme $zoneFerme): static
    {
        if ($this->zonesFermes->removeElement($zoneFerme)) {
            if ($zoneFerme->getFerme() === $this) {
                $zoneFerme->setFerme(null);
            }
        }
        return $this;
    }

    public function getNombreZonesFermes(): int
    {
        return $this->zonesFermes->count();
    }

    public function getFamillesBetailFerme(): Collection
    {
        return $this->famillesBetailFerme;
    }

    public function addFamillesBetailFerme(FamilleBetailFerme $famillesBetailFerme): static
    {
        if (!$this->famillesBetailFerme->contains($famillesBetailFerme)) {
            $this->famillesBetailFerme->add($famillesBetailFerme);
            $famillesBetailFerme->setFerme($this);
        }
        return $this;
    }

    public function removeFamillesBetailFerme(FamilleBetailFerme $famillesBetailFerme): static
    {
        if ($this->famillesBetailFerme->removeElement($famillesBetailFerme)) {
            if ($famillesBetailFerme->getFerme() === $this) {
                $famillesBetailFerme->setFerme(null);
            }
        }
        return $this;
    }

    /**
     * Retourne les familles de bétail déjà associées à cette ferme
     */
    public function getFamillesBetailAssociees(): array
    {
        return $this->famillesBetailFerme
            ->filter(fn(FamilleBetailFerme $familleFerme) => $familleFerme->isActif())
            ->map(fn(FamilleBetailFerme $familleFerme) => $familleFerme->getFamille())
            ->toArray();
    }

    public function __toString(): string
    {
        return $this->nom ?? 'Ferme #' . $this->id;
    }
}