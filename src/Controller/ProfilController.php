<?php

namespace App\Controller;

use App\Entity\Users;
use App\Repository\CommandesRepository;
use App\Repository\UsersRepository;
use Dom\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use FiberError;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(UsersRepository $usersRepository, CommandesRepository $commandesRepository): Response
    {
        $user = $usersRepository->find($this->getUser());

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }
        $commandes = $commandesRepository->findByUserWithDetails($user);

        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
            'user' => $user,
            'commandes' => $commandes,

        ]);
    }

    #[Route('profil/modif', name: 'app_profil_modif')]
    public function modif(Request $request,  EntityManagerInterface $em, UsersRepository $usersRepository): Response
    {
        $user = $usersRepository->find($this->getUser());

        $form = $this->createFormBuilder($user)
            ->add('username', TextType::class)
            ->add('email', EmailType::class)
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('portable', TextType::class)
            ->add('add_livraison', TextType::class)
            ->add('ville', TextType::class)
            ->add('code_postal', TextType::class)
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_profil');
        }


        return $this->render('profil/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/profil/delete', name: 'profil_delete', methods: ['POST'])]
    public function delete(Request $request, EntityManagerInterface $em, TokenStorageInterface $tokenStorage): Response
    {
        /** @var Users|null $user */
        $user = $this->getUser();

        if (!$user instanceof Users) {
            return $this->redirectToRoute('app_login');
        }


        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {

            foreach ($user->getFavoris() as $favori) {
                $em->remove($favori);
            }

            foreach ($user->getAvis() as $avis) {
                $em->remove($avis);
            }

            foreach ($user->getCommandes() as $commande) {
                $commande->setUser(null);
            }

            $em->remove($user);
            $em->flush();

            $tokenStorage->setToken(null);
            $request->getSession()->invalidate();
        }

        return $this->redirectToRoute('app_home');
    }
}
