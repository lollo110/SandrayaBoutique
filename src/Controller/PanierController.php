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
use App\Entity\Commandes;
use App\Entity\Users;
use App\Entity\DetailsCommandes;
use App\Entity\Paiements;
use App\Enum\Paiement;
use App\Enum\Statut;
use App\Enum\StatutPaiement;
use Doctrine\ORM\EntityManagerInterface;

final class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(SessionInterface $session, ProduitsRepository $repo): Response
    {
        $cart = $session->get('cart', []);

    $items = [];
    $total = 0;

    foreach ($cart as $id => $qty) {
        $produit = $repo->find($id);
        if (!$produit) {
            continue;
        }

        $itemTotal = $produit->getPrix() * $qty;
        $total += $itemTotal;

        $items[] = [
            'produit' => $produit,
            'qty' => $qty,
            'total' => $itemTotal,
            'image' => $produit->getProduitsImages()->first()?->getImage()
        ];
    }

    return $this->render('panier/index.html.twig', [
        'items' => $items,
        'total' => $total
    ]);
    }


    #[Route('/panier/ajouter/{id}', name: 'app_panier_add')]
    public function addToPanier(int $id, Request $request, SessionInterface $session, ProduitsRepository $produitRepo): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $qty = $data['qty'] ?? 1;

        $cart = $session->get('cart', []);

        if (isset($cart[$id])) {
    $cart[$id] += $qty;
} else {
    $cart[$id] = $qty;
}

if ($cart[$id] <= 0) {
    unset($cart[$id]);
}

        $session->set('cart', $cart);

        $produit = $produitRepo->find($id);

        $produitData = [
            'id' => $produit->getId(),
            'nom' => $produit->getNomProd(),
            'prix' => $produit->getPrix(),
            'qty' => $cart[$id],
            'images' => []
        ];

        foreach ($produit->getProduitsImages() as $image) {
            $produitData['images'][] = '/assets/uploads/' . $image->getImage();
        }

        $response = new JsonResponse([
            'produit' => $produitData,
            $nb = array_sum($cart)
        ]);

        if ($this->getUser()) {
            $cookie = Cookie::create('cartCookie')
                ->withValue(json_encode($cart))
                ->withExpires(new \DateTime('+7 days'))
                ->withPath('/');

            $response->headers->setCookie($cookie);
        }

        return $response;
    }

    #[Route('/panier/vider', name: 'app_panier_clear', methods: ['POST'])]
    public function clearCart(SessionInterface $session): JsonResponse
    {
        $session->set('cart', []);
        setcookie('cartCookie', '', time() - 3600, '/');

        $response = new JsonResponse(['success' => true]);

        if ($this->getUser()) {
            $cookie = Cookie::create('cartCookie')
                ->withValue('')
                ->withExpires(new \DateTime('-1 days'))
                ->withPath('/');

            $response->headers->setCookie($cookie);
        }

        return $response;
    }

    #[Route('/panier/get', name: 'app_panier_get', methods: ['GET'])]
public function getPanier(SessionInterface $session, ProduitsRepository $repo): JsonResponse
{
    $cart = $session->get('cart', []);
    $items = [];

    foreach ($cart as $id => $qty) {
        $produit = $repo->find($id);
        if (!$produit) continue;

        $items[] = [
            'id' => $produit->getId(),
            'nom' => $produit->getNomProd(),
            'prix' => $produit->getPrix(),
            'qty' => $qty,
            'images' => [
                '/assets/uploads/' . $produit->getProduitsImages()->first()?->getImage()
            ]
        ];
    }

    return new JsonResponse(['items' => $items]);
}

#[Route('/panier/finaliser', name: 'app_panier_finaliser', methods: ['POST'])]
public function finaliserCommande(
    SessionInterface $session,
    ProduitsRepository $produitRepo,
    EntityManagerInterface $em
): Response {

    /**
     * @var  Users $user
     */
    $user = $this->getUser();

    if (!$user) {
        return $this->redirectToRoute('app_login');
    }

    $cart = $session->get('cart', []);
    if (empty($cart)) {
        return $this->redirectToRoute('app_panier');
    }

    // 🧾 Création commande
    $commande = new Commandes();
    $commande->setIdUser($user);
    $commande->setStatut(Statut::ENATTENTE); // ou EN_ATTENTE
    $commande->setAddLivraison($user->getAddLivraison()); // adapte si besoin

    $total = 0;

    foreach ($cart as $idProduit => $quantite) {
        $produit = $produitRepo->find($idProduit);
        if (!$produit) continue;

        $details = new DetailsCommandes();
        $details->setIdCommande($commande);
        $details->setIdProduit($produit);
        $details->setQuantite($quantite);
        $details->setPrix($produit->getPrix());

        $total += $produit->getPrix() * $quantite;

        $em->persist($details);
    }

    $paiement = new Paiements();
$paiement->setMontant($total);
$paiement->setModePaiement(Paiement::CARTE);
$paiement->setStatut(StatutPaiement::ENATTENTE);

$commande->setPaiements($paiement);

    $commande->setTotal($total);

    $em->persist($paiement);

    $em->persist($commande);
    $em->flush();

    // 🧹 Vider le panier
    $session->remove('cart');

    return $this->redirectToRoute('app_panier_success', [
        'id' => $commande->getId(),
    ]);
}

#[Route('/panier/success/{id}', name: 'app_panier_success')]
public function success(Commandes $commande): Response
{
    return $this->render('panier/success.html.twig', [
        'commande' => $commande,
        
    ]);
}

}