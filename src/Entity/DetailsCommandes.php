<?php

namespace App\Entity;

use App\Repository\DetailsCommandesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DetailsCommandesRepository::class)]
class DetailsCommandes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\ManyToOne(inversedBy: 'DetailsCommandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Commandes $commande = null;

    #[ORM\ManyToOne(inversedBy: 'DetailsCommandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produits $produit = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getIdCommande(): ?Commandes
    {
        return $this->commande;
    }

    public function setIdCommande(?Commandes $commande): static
    {
        $this->commande = $commande;

        return $this;
    }

    public function getIdProduit(): ?Produits
    {
        return $this->produit;
    }

    public function setIdProduit(?Produits $produit): static
    {
        $this->produit = $produit;

        return $this;
    }
}
