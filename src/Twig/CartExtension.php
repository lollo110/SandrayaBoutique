<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Repository\ProduitsRepository;

class CartExtension extends AbstractExtension implements GlobalsInterface
{
    private $requestStack;
    private $produitRepo;


    public function __construct(RequestStack $requestStack, ProduitsRepository $produitRepo)
    {
        $this->requestStack = $requestStack;
        $this->produitRepo = $produitRepo;
    }

    public function getGlobals(): array
{
    $session = $this->requestStack->getSession();

    // Sécurisation : la session peut être null
    if (!$session) {
        $cart = [];
    } else {
        $cart = $session->get('cart', []);
    }

    // Par sécurité : on force un tableau
    if (!is_array($cart)) {
        $cart = [];
    }

    $cartItems = [];
    $totalQty = 0;
    $totalPrice = 0;

    foreach ($cart as $id => $qty) {

        $produit = $this->produitRepo->find($id);

        if ($produit) {

            // Sécurité pour l’image
            $images = $produit->getProduitsImages();
            $image = ($images && $images->first())
                ? '/assets/uploads/' . $images->first()->getImage()
                : null;

            $cartItems[] = [
                'id' => $produit->getId(),
                'nom' => $produit->getNomProd(),
                'prix' => $produit->getPrix(),
                'qty' => $qty,
                'image' => $image
            ];

            $totalQty += $qty;
            $totalPrice += $produit->getPrix() * $qty;
        }
    }

    return [
        'cartItems' => $cartItems,
        'totalQty' => $totalQty,
        'totalPrice' => $totalPrice
    ];
}
}