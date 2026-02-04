<?php

namespace App\Controller;

use App\Entity\Favoris;
use App\Entity\Produits;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FavorisController extends AbstractController
{
    #[Route('/favoris/{id}', name: 'app_favoris', methods: ['POST'])]
    public function index(Produits $produit, EntityManagerInterface $em): JsonResponse
    {

        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Non connecté'
            ], 401);
        }

        $repo = $em->getRepository(Favoris::class);

        $favori = $repo->findOneBy([
            'user' => $user,
            'produit' => $produit
        ]);

        if ($favori) {
            $em->remove($favori);
            $em->flush();

            return new JsonResponse([
                'success' => true,
                'action' => 'removed'
            ]);
        }

        $favori = new Favoris();
        $favori->setUser($user);
        $favori->setProduit($produit);

        $em->persist($favori);
        $em->flush();

        return new JsonResponse([
            'success' => true,
            'action' => 'added'
        ]);
    }

    #[Route('/favoris', name: 'app_favoris_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $favoris = $em->getRepository(Favoris::class)
            ->findBy(['user' => $user]);


        return $this->render('favoris/index.html.twig', [
            'favoris' => $favoris
        ]);
    }

    #[Route('/favoris/supprimer/{id}', name: 'ajax_supprimer_favoris', methods: ['POST'])]
public function supprimerFavori(
    Favoris $favori,
    EntityManagerInterface $em
): JsonResponse {
    $user = $this->getUser();

    if (!$user || $favori->getUser() !== $user) {
        return new JsonResponse([
            'success' => false,
            'message' => 'Action interdite'
        ], 403);
    }

    $em->remove($favori);
    $em->flush();

    return new JsonResponse([
        'success' => true,
        'id' => $favori->getId()
    ]);
}
}