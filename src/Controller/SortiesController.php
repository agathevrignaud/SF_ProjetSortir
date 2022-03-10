<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Repository\SortieRepository;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Entity\Etat;
use App\Repository\EtatRepository;
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

    #[Route('/sortie/{id}/{nouvelEtat}', name: 'sortie_etat')]
    public function publierSortie(int $id, string $nouvelEtat,  SortieRepository $sortieRepository, EtatRepository $etatRepository, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $sortie = $sortieRepository->find($id);
        $etat = $etatRepository->findBy(['libelle' => $nouvelEtat]);
        $sortie->setEtat($etat[0]);
        $entityManager->persist($sortie);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Votre sortie '.$sortie->getNom().' est désormais '. $nouvelEtat.' !'
        );

        return $this->redirectToRoute('home');
    }

    #[Route('/sortie/{action}/{id}/{userId}', name: 'sortie_inscription_desistement')]
    public function inscription(int $id, int $userId, string $action, SortieRepository $sortieRepository, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $sortie = $sortieRepository->find($id);
        $user = $userRepository->find($userId);
        $flashMesssage = '';
        switch ($action) {
            case 'inscription':
                $sortie->addSortiesParticipant($user);
                $flashMesssage = 'Vous êtes inscrit(e) à la sortie '. $sortie->getNom().' !';
                break;
            case 'desistement':
                $sortie->removeSortiesParticipant($user);
                $flashMesssage = 'Vous êtes désinscrit(e) de la sortie '. $sortie->getNom().' !';;
                break;
        }
        $entityManager->persist($sortie);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            $flashMesssage
        );

        return $this->redirectToRoute('home');
    }


}
