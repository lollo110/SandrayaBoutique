<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Repository\AvisRepository;
use App\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProduitsRepository $produitsRepository,
    AvisRepository $avisRepository): Response
    {
        $products = $produitsRepository->findOneProductPerCategoryWithImages();
        $avis = $avisRepository->findRandomAvis(3);

        return $this->render('home/index.html.twig', [
            "produits" => $products,
            "avis" => $avis
        ]);
    }
}
