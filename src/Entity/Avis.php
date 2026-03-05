<?php

namespace App\Entity;

use App\Repository\AvisRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvisRepository::class)]
class Avis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $note = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $commentaire = null;

    #[ORM\Column]
    private ?\DateTime $date_avis = null;

    #[ORM\ManyToOne(inversedBy: 'Avis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $user = null;

    #[ORM\ManyToOne(inversedBy: 'Avis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produits $produit = null;

    public function __construct()
    {
        $this->date_avis = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getDateAvis(): ?\DateTime
    {
        return $this->date_avis;
    }

    public function setDateAvis(\DateTime $date_avis): void
    {
        $this->date_avis = new \DateTime;

    }

    // src/Entity/Avis.php

public function getUser(): ?Users
{
    return $this->user;
}

public function setUser(?Users $user): static
{
    $this->user = $user;
    return $this;
}

public function getProduit(): ?Produits
{
    return $this->produit;
}

public function setProduit(?Produits $produit): static
{
    $this->produit = $produit;
    return $this;
}
}
