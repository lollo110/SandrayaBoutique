<?php

namespace App\Controller;

use App\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProduitsRepository $produitsRepository): Response
    {
        $products = $produitsRepository->findOneProductPerCategoryWithImages();

        return $this->render('home/index.html.twig', [
            "produits" => $products
        ]);
    }
}
