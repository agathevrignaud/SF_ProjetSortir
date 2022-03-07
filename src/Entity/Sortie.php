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

    #[ORM\Column(type: 'time', nullable: true)]
    private $durée;

    #[ORM\Column(type: 'date')]
    private $dateLimiteIncription;

    #[ORM\Column(type: 'smallint')]
    private $nbInscriptionsMax;

    #[ORM\Column(type: 'text', nullable: true)]
    private $infoSortie;

    #[ORM\ManyToOne(targetEntity: Etat::class, inversedBy: 'sorties')]
    #[ORM\JoinColumn(nullable: false)]
    private $etat;

    #[ORM\ManyToOne(targetEntity: Participant::class, inversedBy: 'sorties')]
    #[ORM\JoinColumn(nullable: false)]
    private $organisateur;

    #[ORM\ManyToMany(targetEntity: Participant::class, inversedBy: 'sorties')]
    private $participantInscrit;

    #[ORM\ManyToOne(targetEntity: Site::class, inversedBy: 'sorties')]
    #[ORM\JoinColumn(nullable: false)]
    private $site;

    public function __construct()
    {
        $this->participantInscrit = new ArrayCollection();
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

    public function getDurée(): ?\DateTimeInterface
    {
        return $this->durée;
    }

    public function setDurée(?\DateTimeInterface $durée): self
    {
        $this->durée = $durée;

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

    public function getOrganisateur(): ?Participant
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?Participant $organisateur): self
    {
        $this->organisateur = $organisateur;

        return $this;
    }

    /**
     * @return Collection<int, Participant>
     */
    public function getParticipantInscrit(): Collection
    {
        return $this->participantInscrit;
    }

    public function addParticipantInscrit(Participant $participantInscrit): self
    {
        if (!$this->participantInscrit->contains($participantInscrit)) {
            $this->participantInscrit[] = $participantInscrit;
        }

        return $this;
    }

    public function removeParticipantInscrit(Participant $participantInscrit): self
    {
        $this->participantInscrit->removeElement($participantInscrit);

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
}
