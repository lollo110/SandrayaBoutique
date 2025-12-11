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
        $session = $this->requestStack->getSession(); // Récupérer la session
        $cart = $session ? $session->get('cart', []) : [];
        // dd($cart);
        $cartItems = [];
        $totalQty = 0;
        $totalPrice = 0;

        foreach ($cart as $id => &$qty) {
            $produit = $this->produitRepo->find($id);
            if ($produit) {
                $cartItems[] = [
                    'id' => $produit->getId(),
                    'nom' => $produit->getNomProd(),
                    'prix' => $produit->getPrix(),
                    'qty' => $qty,
                    'image' => $produit->getProduitsImages()[0] ? '/assets/uploads/' . $produit->getProduitsImages()[0]->getImage() : null
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