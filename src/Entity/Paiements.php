<?php

namespace App\Entity;

use App\Enum\Paiement;
use App\Enum\StatutPaiement;
use App\Repository\PaiementsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaiementsRepository::class)]
class Paiements
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\Column(enumType: Paiement::class)]
    private ?Paiement $mode_paiement = null;

    #[ORM\Column(enumType: StatutPaiement::class)]
    private ?StatutPaiement $statut = null;

    #[ORM\Column]
    private ?\DateTime $date_paiement = null;

    #[ORM\OneToOne(inversedBy: 'paiements', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Commandes $commande = null;

    public function __construct()
    {
        $this->date_paiement = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getModePaiement(): ?Paiement
    {
        return $this->mode_paiement;
    }

    public function setModePaiement(Paiement $mode_paiement): static
    {
        $this->mode_paiement = $mode_paiement;

        return $this;
    }

    public function getStatut(): ?StatutPaiement
    {
        return $this->statut;
    }

    public function setStatut(StatutPaiement $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getDatePaiement(): ?\DateTime
    {
        return $this->date_paiement;
    }

    public function setDatePaiement(\DateTime $date_paiement): void
    {
        $this->date_paiement = new \DateTime;

    }

    public function getCommande(): ?Commandes
    {
        return $this->commande;
    }

    public function setCommande(Commandes $commande): static
    {
        $this->commande = $commande;

        return $this;
    }
}
