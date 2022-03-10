<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SortieRepository::class)]
class Sortie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    private $nom;

    #[ORM\Column(type: 'datetime')]
    private $dateHeureDebut;

    #[ORM\Column(type: 'date')]
    private $dateLimiteIncription;

    #[ORM\Column(type: 'smallint')]
    private $nbInscriptionsMax;

    #[ORM\Column(type: 'text', nullable: true)]
    private $infoSortie;

    #[ORM\ManyToOne(targetEntity: Etat::class, inversedBy: 'sorties')]
    #[ORM\JoinColumn(nullable: false)]
    private $etat;

    #[ORM\ManyToOne(targetEntity: Site::class, inversedBy: 'sorties')]
    #[ORM\JoinColumn(nullable: false)]
    private $site;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'sortiesOrganisateur')]
    #[ORM\JoinColumn(nullable: false)]
    private $organisateur;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'participant')]
    private $sortiesParticipants;

    #[ORM\Column(type: 'smallint')]
    private $duree;

    #[ORM\ManyToOne(targetEntity: Lieu::class, inversedBy: 'sorties')]
    #[ORM\JoinColumn(nullable: false)]
    private $lieu;

<<<<<<< HEAD
    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private $motifAnnulation;
=======
>>>>>>> Added sign up / bail out fonctions on trips page

    public function __construct()
    {
        $this->sortiesParticipants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getDateHeureDebut(): ?\DateTimeInterface
    {
        return $this->dateHeureDebut;
    }

    public function setDateHeureDebut(\DateTimeInterface $dateHeureDebut): self
    {
        $this->dateHeureDebut = $dateHeureDebut;
        return $this;
    }

    public function getDateLimiteIncription(): ?\DateTimeInterface
    {
        return $this->dateLimiteIncription;
    }

    public function setDateLimiteIncription(\DateTimeInterface $dateLimiteIncription): self
    {
        $this->dateLimiteIncription = $dateLimiteIncription;
        return $this;
    }

    public function getNbInscriptionsMax(): ?int
    {
        return $this->nbInscriptionsMax;
    }

    public function setNbInscriptionsMax(int $nbInscriptionsMax): self
    {
        $this->nbInscriptionsMax = $nbInscriptionsMax;
        return $this;
    }

    public function getInfoSortie(): ?string
    {
        return $this->infoSortie;
    }

    public function setInfoSortie(?string $infoSortie): self
    {
        $this->infoSortie = $infoSortie;
        return $this;
    }

    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    public function setEtat(?Etat $etat): self
    {
        $this->etat = $etat;
        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

        return $this;
    }

    public function getOrganisateur(): ?User
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?User $organisateur): self
    {
        $this->organisateur = $organisateur;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getSortiesParticipants(): Collection
    {
        return $this->sortiesParticipants;
    }

    public function addSortiesParticipant(User $sortiesParticipant): self
    {
        if (!$this->sortiesParticipants->contains($sortiesParticipant)) {
            $this->sortiesParticipants[] = $sortiesParticipant;
            $sortiesParticipant->addParticipant($this);
        }

        return $this;
    }

    public function removeSortiesParticipant(User $sortiesParticipant): self
    {
        if ($this->sortiesParticipants->removeElement($sortiesParticipant)) {
            $sortiesParticipant->removeParticipant($this);
        }
        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    public function setLieu(?Lieu $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getMotifAnnulation(): ?string
    {
        return $this->motifAnnulation;
    }

    public function setMotifAnnulation(?string $motifAnnulation): self
    {
        $this->motifAnnulation = $motifAnnulation;

        return $this;
    }




}
