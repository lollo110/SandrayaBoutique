<?php

namespace App\Controller;

use App\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(): Response
    {
        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
        ]);
    }


    #[Route('/panier/ajouter/{id}', name: 'app_panier_add')]
    public function addToPanier(int $id, Request $request, SessionInterface $session, ProduitsRepository $produitRepo): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $qty = $data['qty'] ?? 1;

        $cart = $session->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id] += $qty; // augmenter la quantité
        } else {
            $cart[$id] = $qty;
        }

        $session->set('cart', $cart);

        $produit = $produitRepo->find($id);

        $produitData = [
            'id' => $produit->getId(),
            'nom' => $produit->getNomProd(),
            'prix' => $produit->getPrix(),
            'qty' => $cart[$id], // quantité totale dans le panier
            'images' => []
        ];

        foreach ($produit->getProduitsImages() as $image) {
            $produitData['images'][] = '/assets/uploads/' . $image->getImage();
        }

        $response = new JsonResponse([
            'produit' => $produitData,
            'nb' => count($cart)
        ]);

        $cookie = Cookie::create('cartCookie')
            ->withValue(json_encode($cart))
            ->withExpires(new \DateTime('+7 days'))
            ->withPath('/');

        $response->headers->setCookie($cookie);

        return $response;
    }

    #[Route('/panier/vider', name: 'app_panier_clear', methods: ['POST'])]
    public function clearCart(SessionInterface $session): JsonResponse
    {
        $session->set('cart', []);
        setcookie('cartCookie', '', time() - 3600, '/');

        $response = new JsonResponse(['success' => true]);

        $cookie = Cookie::create('cartCookie')
            ->withValue(json_encode($session->get('cart', [])))
            ->withExpires(new \DateTime('-1 days'))
            ->withPath('/');

        $response->headers->setCookie($cookie);

        return $response;
    }
}
