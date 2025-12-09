<?php

namespace App\Entity;

use App\Enum\Statut;
use App\Repository\CommandesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandesRepository::class)]
class Commandes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $date_commande = null;

    #[ORM\Column]
    private ?float $total = null;

    #[ORM\Column(enumType: Statut::class)]
    private ?Statut $statut = null;

    #[ORM\Column(length: 255)]
    private ?string $add_livraison = null;

    #[ORM\ManyToOne(inversedBy: 'Commandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?users $user = null;

    /**
     * @var Collection<int, DetailsCommandes>
     */
    #[ORM\OneToMany(targetEntity: DetailsCommandes::class, mappedBy: 'Commandes')]
    private Collection $detailsCommandes;

    #[ORM\OneToOne(mappedBy: 'Commandes', cascade: ['persist', 'remove'])]
    private ?Paiements $paiements = null;

    public function __construct()
    {
        $this->detailsCommandes = new ArrayCollection();

        $this->date_commande = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
    
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCommande(): ?\DateTime
    {
        return $this->date_commande;
    }

    public function setDateCommande(\DateTime $date_commande): void
    {
        $this->date_commande = new \DateTime();

    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getStatut(): ?Statut
    {
        return $this->statut;
    }

    public function setStatut(Statut $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getAddLivraison(): ?string
    {
        return $this->add_livraison;
    }

    public function setAddLivraison(string $add_livraison): static
    {
        $this->add_livraison = $add_livraison;

        return $this;
    }

    public function getIdUser(): ?Users
    {
        return $this->user;
    }

    public function setIdUser(?Users $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, DetailsCommandes>
     */
    public function getDetailsCommandes(): Collection
    {
        return $this->detailsCommandes;
    }

    public function addDetailsCommande(DetailsCommandes $detailsCommande): static
    {
        if (!$this->detailsCommandes->contains($detailsCommande)) {
            $this->detailsCommandes->add($detailsCommande);
            $detailsCommande->setIdCommande($this);
        }

        return $this;
    }

    public function removeDetailsCommande(DetailsCommandes $detailsCommande): static
    {
        if ($this->detailsCommandes->removeElement($detailsCommande)) {
            // set the owning side to null (unless already changed)
            if ($detailsCommande->getIdCommande() === $this) {
                $detailsCommande->setIdCommande(null);
            }
        }

        return $this;
    }

    public function getPaiements(): ?Paiements
    {
        return $this->paiements;
    }

    public function setPaiements(Paiements $paiements): static
    {
        // set the owning side of the relation if necessary
        if ($paiements->getIdCommande() !== $this) {
            $paiements->setIdCommande($this);
        }

        $this->paiements = $paiements;

        return $this;
    }
}
