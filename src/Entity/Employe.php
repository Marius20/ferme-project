<?php

namespace App\Entity;

use App\Repository\EmployeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeRepository::class)]
#[ORM\Table(name: 'employes')]
class Employe
{
    const ROLE_BERGER = 'berger';
    const ROLE_OUVRIER = 'ouvrier';
    const ROLE_VENDEUR = 'vendeur';
    const ROLE_VETERINAIRE = 'veterinaire';
    const ROLE_RESPONSABLE = 'responsable';
    const ROLE_GERANT = 'gerant';

    const STATUT_ACTIF = 'actif';
    const STATUT_INACTIF = 'inactif';
    const STATUT_CONGE = 'conge';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $prenom = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 180, unique: true, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(length: 50)]
    private ?string $role = null;

    #[ORM\Column(length: 20)]
    private ?string $statut = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $salaireMensuel = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateEmbauche = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateFinContrat = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $competences = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateModification = null;

    #[ORM\ManyToOne(targetEntity: Ferme::class, inversedBy: 'employes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ferme $ferme = null;

    #[ORM\OneToMany(targetEntity: TacheEmploye::class, mappedBy: 'employe', orphanRemoval: true)]
    private Collection $taches;

    #[ORM\OneToMany(targetEntity: PresenceEmploye::class, mappedBy: 'employe', orphanRemoval: true)]
    private Collection $presences;

    public function __construct()
    {
        $this->taches = new ArrayCollection();
        $this->presences = new ArrayCollection();
        $this->dateCreation = new \DateTime();
        $this->statut = self::STATUT_ACTIF;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;
        return $this;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;
        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;
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

    public function getSalaireMensuel(): ?string
    {
        return $this->salaireMensuel;
    }

    public function setSalaireMensuel(?string $salaireMensuel): static
    {
        $this->salaireMensuel = $salaireMensuel;
        return $this;
    }

    public function getDateEmbauche(): ?\DateTimeInterface
    {
        return $this->dateEmbauche;
    }

    public function setDateEmbauche(?\DateTimeInterface $dateEmbauche): static
    {
        $this->dateEmbauche = $dateEmbauche;
        return $this;
    }

    public function getDateFinContrat(): ?\DateTimeInterface
    {
        return $this->dateFinContrat;
    }

    public function setDateFinContrat(?\DateTimeInterface $dateFinContrat): static
    {
        $this->dateFinContrat = $dateFinContrat;
        return $this;
    }

    public function getCompetences(): ?string
    {
        return $this->competences;
    }

    public function setCompetences(?string $competences): static
    {
        $this->competences = $competences;
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

    public function getTaches(): Collection
    {
        return $this->taches;
    }

    public function addTache(TacheEmploye $tache): static
    {
        if (!$this->taches->contains($tache)) {
            $this->taches->add($tache);
            $tache->setEmploye($this);
        }
        return $this;
    }

    public function removeTache(TacheEmploye $tache): static
    {
        if ($this->taches->removeElement($tache)) {
            if ($tache->getEmploye() === $this) {
                $tache->setEmploye(null);
            }
        }
        return $this;
    }

    public function getPresences(): Collection
    {
        return $this->presences;
    }

    public function addPresence(PresenceEmploye $presence): static
    {
        if (!$this->presences->contains($presence)) {
            $this->presences->add($presence);
            $presence->setEmploye($this);
        }
        return $this;
    }

    public function removePresence(PresenceEmploye $presence): static
    {
        if ($this->presences->removeElement($presence)) {
            if ($presence->getEmploye() === $this) {
                $presence->setEmploye(null);
            }
        }
        return $this;
    }

    public function getNomComplet(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }

    public function isActif(): bool
    {
        return $this->statut === self::STATUT_ACTIF;
    }

    public function getAnciennete(): ?int
    {
        if (!$this->dateEmbauche) {
            return null;
        }

        $dateReference = $this->dateFinContrat ?? new \DateTime();
        return $this->dateEmbauche->diff($dateReference)->days;
    }

    public function getTachesEnCours(): Collection
    {
        return $this->taches->filter(function(TacheEmploye $tache) {
            return !$tache->isTerminee();
        });
    }

    public function getTachesJour(\DateTime $date = null): Collection
    {
        $date = $date ?? new \DateTime();
        
        return $this->taches->filter(function(TacheEmploye $tache) use ($date) {
            return $tache->getDatePrevue()->format('Y-m-d') === $date->format('Y-m-d');
        });
    }

    public static function getRolesDisponibles(): array
    {
        return [
            self::ROLE_BERGER => 'Berger',
            self::ROLE_OUVRIER => 'Ouvrier',
            self::ROLE_VENDEUR => 'Vendeur',
            self::ROLE_VETERINAIRE => 'Vétérinaire',
            self::ROLE_RESPONSABLE => 'Responsable',
            self::ROLE_GERANT => 'Gérant',
        ];
    }

    public static function getStatutsDisponibles(): array
    {
        return [
            self::STATUT_ACTIF => 'Actif',
            self::STATUT_INACTIF => 'Inactif',
            self::STATUT_CONGE => 'En congé',
        ];
    }

    public function __toString(): string
    {
        return $this->getNomComplet() . ' (' . $this->role . ')';
    }
}