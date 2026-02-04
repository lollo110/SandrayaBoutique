<?php

namespace App\Controller;

use App\Entity\Favoris;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class ProduitsController extends AbstractController
{
    #[Route('/produits', name: 'app_produits')]
    public function index(ProduitsRepository $produitsRepository, EntityManagerInterface $em): Response
    {

        $products = $produitsRepository->findAllWithImages(); 

        $user = $this->getUser();

    $favorisIds = [];

    if ($user) {
        $favoris = $em->getRepository(Favoris::class)
            ->findBy(['user' => $user]);

        foreach ($favoris as $fav) {
            $favorisIds[] = $fav->getProduit()->getId();
        }
    }
        return $this->render('produits/index.html.twig', [
            'controller_name' => 'ProduitsController',
            'produits' => $products,
            'favorisIds' => $favorisIds 
        ]);
    }

    
}
