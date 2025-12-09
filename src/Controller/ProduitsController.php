<?php

namespace App\Controller;

use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProduitsController extends AbstractController
{
    #[Route('/produits', name: 'app_produits')]
    public function index(ProduitsRepository $produitsRepository): Response
    {
        $products = $produitsRepository->findAllWithImages(); 

        return $this->render('produits/index.html.twig', [
            'controller_name' => 'ProduitsController',
            'produits' => $products, 
        ]);
    }
}
