<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Entity\ProduitsImages;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DetailProduitController extends AbstractController
{
    #[Route('/detail/produit/{id}', name: 'app_detail_produit')]
    public function index(int $id,ProduitsRepository $produitsRepository): Response
    {
        $produit = $produitsRepository->findOneByIdWithImages($id);


        return $this->render('detail_produit/index.html.twig', [
            'produit' => $produit
        ]);
    }
}
