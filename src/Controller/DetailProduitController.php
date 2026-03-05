<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Produits;
use App\Entity\ProduitsImages;
use App\Form\AvisType;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DetailProduitController extends AbstractController
{
   #[Route('/detail/produit/{id}', name: 'app_detail_produit')]
public function index(
    int $id,
    ProduitsRepository $produitsRepository,
    Request $request,
    EntityManagerInterface $em
): Response {
    $produit = $produitsRepository->findOneByIdWithImagesAndAvis($id);

    if (!$produit) {
        throw $this->createNotFoundException('Produit non trouvé');
    }

    $avis = new Avis();
    $avis->setProduit($produit);
    $avis->setUser($this->getUser()); // utilisateur connecté

    $form = $this->createForm(AvisType::class, $avis);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($avis);
        $em->flush();

        $this->addFlash('success', 'Avis ajouté avec succès');

        return $this->redirectToRoute('app_detail_produit', [
            'id' => $produit->getId(),
        ]);
    }

    return $this->render('detail_produit/index.html.twig', [
        'produit' => $produit,
        'avisForm' => $form->createView(),
    ]);
}

#[Route('/avis/delete/{id}', name: 'delete_avis')]
public function deleteAvis(
    Avis $avis,
    EntityManagerInterface $em
): Response {

    if ($avis->getUser() !== $this->getUser()) {
        throw $this->createAccessDeniedException();
    }

    $produitId = $avis->getProduit()->getId();

    $em->remove($avis);
    $em->flush();

    return $this->redirectToRoute('app_detail_produit', [
        'id' => $produitId
    ]);
}
}
