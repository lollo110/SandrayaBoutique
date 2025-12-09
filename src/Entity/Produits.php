<?php

namespace App\Entity;

use App\Repository\ProduitsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitsRepository::class)]
class Produits
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_prod = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column]
    private ?int $stock = null;

    /**
     * @var Collection<int, DetailsCommandes>
     */
    #[ORM\OneToMany(targetEntity: DetailsCommandes::class, mappedBy: 'produit')]
    private Collection $detailsCommandes;

    /**
     * @var Collection<int, Avis>
     */
    #[ORM\OneToMany(targetEntity: Avis::class, mappedBy: 'produit')]
    private Collection $avis;

    /**
     * @var Collection<int, ProduitsImages>
     */
    #[ORM\OneToMany(targetEntity: ProduitsImages::class, mappedBy: 'produit')]
    private Collection $produitsImages;

    /**
     * @var Collection<int, Favoris>
     */
    #[ORM\OneToMany(targetEntity: Favoris::class, mappedBy: 'produit')]
    private Collection $favoris;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $category = null;

    public function __construct()
    {
        $this->detailsCommandes = new ArrayCollection();
        $this->avis = new ArrayCollection();
        $this->produitsImages = new ArrayCollection();
        $this->favoris = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomProd(): ?string
    {
        return $this->nom_prod;
    }
    
    public function __toString(): string
{
    return $this->getId() . ' - ' . $this->getNomProd(); 
}

    public function setNomProd(string $nom_prod): static
    {
        $this->nom_prod = $nom_prod;

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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

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
            $detailsCommande->setIdProduit($this);
        }

        return $this;
    }

    public function removeDetailsCommande(DetailsCommandes $detailsCommande): static
    {
        if ($this->detailsCommandes->removeElement($detailsCommande)) {
            // set the owning side to null (unless already changed)
            if ($detailsCommande->getIdProduit() === $this) {
                $detailsCommande->setIdProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Avis>
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvi(Avis $avi): static
    {
        if (!$this->avis->contains($avi)) {
            $this->avis->add($avi);
            $avi->setIdProduit($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avi): static
    {
        if ($this->avis->removeElement($avi)) {
            // set the owning side to null (unless already changed)
            if ($avi->getIdProduit() === $this) {
                $avi->setIdProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProduitsImages>
     */
    public function getProduitsImages(): Collection
    {
        return $this->produitsImages;
    }

    public function addProduitsImage(ProduitsImages $produitsImage): static
    {
        if (!$this->produitsImages->contains($produitsImage)) {
            $this->produitsImages->add($produitsImage);
            $produitsImage->setProduit($this);
        }

        return $this;
    }

    public function removeProduitsImage(ProduitsImages $produitsImage): static
    {
        if ($this->produitsImages->removeElement($produitsImage)) {
            // set the owning side to null (unless already changed)
            if ($produitsImage->getProduit() === $this) {
                $produitsImage->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Favoris>
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavori(Favoris $favori): static
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris->add($favori);
            $favori->setProduit($this);
        }

        return $this;
    }

    public function removeFavori(Favoris $favori): static
    {
        if ($this->favoris->removeElement($favori)) {
            // set the owning side to null (unless already changed)
            if ($favori->getProduit() === $this) {
                $favori->setProduit(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): static
    {
        $this->category = $category;

        return $this;
    }
}
