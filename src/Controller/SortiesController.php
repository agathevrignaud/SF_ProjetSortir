<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Repository\SortieRepository;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortiesController extends AbstractController
{
    #[Route('/sortie/{id}', name: 'sortie_details')]
    public function afficherSortie(int $id, SortieRepository $sortieRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $sortie = $sortieRepository->find($id);
        return $this->render('pages/sortie.html.twig', [
            'sortie' => $sortie
        ]);
    }

    #[Route('/sortie/{id}', name: 'sortie_modifier')]
    public function modifierSortie(int $id, SortieRepository $sortieRepository, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $sortie = $sortieRepository->find($id);

        #récup les infos du form + persist + flush
        return $this->render('pages/sortie.html.twig', [
            'sortie' => $sortie
        ]);
    }

    #[Route('/sortie', name: 'sortie_creer')]
    public function creerSortie(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('pages/sortie.html.twig', [
        ]);
    }

    #[Route('/sortie/{action}/{id}/{userId}', name: 'sortie_inscription_desistement')]
    public function inscription(int $id, int $userId, string $action, SortieRepository $sortieRepository, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $sortie = $sortieRepository->find($id);
        $user = $userRepository->find($userId);
        switch ($action) {
            case 'inscription':
                $sortie->addSortiesParticipant($user);
                break;
            case 'desistement':
                $sortie->removeSortiesParticipant($user);
                break;
        }
        $entityManager->persist($sortie);
        $entityManager->flush();

        return $this->redirectToRoute('home');
    }


}
